<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use App\Models\SaasProductReview;
use App\Models\SaasWallet;
use App\Models\SaasWithdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaasSellerDashboardController extends Controller
{
    /**
     * Display the seller dashboard.
     */
    public function index()
    {
        $sellerId = Auth::id();
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        // Check if seller has a profile
        if (!$sellerProfile) {
            return redirect()->route('seller.profile.create')
                ->with('info', 'Please complete your seller profile first.');
        }

        // Check if seller is approved
        if (!$sellerProfile->is_approved) {
            return view('saas_seller.saas_pending_approval');
        }

        // Get current date ranges
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Wallet information
        $wallet = SaasWallet::firstOrCreate(
            ['user_id' => $sellerId],
            ['balance' => 0, 'pending_balance' => 0]
        );

        // Total products
        $totalProducts = SaasProduct::where('seller_id', $sellerId)->count();

        // Active products
        $activeProducts = SaasProduct::where('seller_id', $sellerId)
            ->where('is_active', true)
            ->count();

        // Out of stock products
        $outOfStockProducts = SaasProduct::where('seller_id', $sellerId)
            ->where('stock', 0)
            ->count();

        // Low stock products (less than 10)
        $lowStockProducts = SaasProduct::where('seller_id', $sellerId)
            ->where('stock', '>', 0)
            ->where('stock', '<', 10)
            ->count();

        // Total orders
        $totalOrders = SaasOrder::where('seller_id', $sellerId)->count();

        // Today's orders
        $todayOrders = SaasOrder::where('seller_id', $sellerId)
            ->whereDate('created_at', $today)
            ->count();

        // Pending orders
        $pendingOrders = SaasOrder::where('seller_id', $sellerId)
            ->where('order_status', 'pending')
            ->count();

        // Total sales (all time)
        $totalSales = SaasOrder::where('seller_id', $sellerId)
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Today's sales
        $todaySales = SaasOrder::where('seller_id', $sellerId)
            ->whereDate('created_at', $today)
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // This month's sales
        $thisMonthSales = SaasOrder::where('seller_id', $sellerId)
            ->where('created_at', '>=', $thisMonth)
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Last month's sales
        $lastMonthSales = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Calculate growth percentage
        $growthPercentage = $lastMonthSales > 0
            ? round((($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 2)
            : 0;

        // Pending withdrawals
        $pendingWithdrawals = SaasWithdrawal::where('user_id', $sellerId)
            ->where('status', 'pending')
            ->sum('amount');

        // Total reviews
        $totalReviews = SaasProductReview::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->count();

        // Average rating
        $averageRating = SaasProductReview::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->avg('rating') ?? 0;

        // Recent orders (last 10)
        $recentOrders = SaasOrder::where('seller_id', $sellerId)
            ->with('customer')
            ->latest()
            ->limit(10)
            ->get();

        // Top selling products
        $topProducts = DB::table('saas_order_items')
            ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
            ->join('saas_products', 'saas_order_items.product_id', '=', 'saas_products.id')
            ->where('saas_orders.seller_id', $sellerId)
            ->where('saas_orders.order_status', '!=', 'cancelled')
            ->select(
                'saas_products.id',
                'saas_products.name',
                'saas_products.price',
                DB::raw('SUM(saas_order_items.quantity) as quantity_sold'),
                DB::raw('SUM(saas_order_items.price * saas_order_items.quantity) as total_sales')
            )
            ->groupBy('saas_products.id', 'saas_products.name', 'saas_products.price')
            ->orderBy('quantity_sold', 'desc')
            ->limit(5)
            ->get();

        // Recent reviews
        $recentReviews = SaasProductReview::whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->with(['product', 'customer'])
            ->latest()
            ->limit(5)
            ->get();

        // Monthly sales chart data (last 12 months)
        $monthlySalesData = SaasOrder::where('seller_id', $sellerId)
            ->where('order_status', '!=', 'cancelled')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(id) as total_orders')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare chart data
        $salesChartLabels = [];
        $salesChartValues = [];
        $ordersChartValues = [];

        // Fill in missing months with zero values
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
            $monthLabel = Carbon::now()->subMonths($i)->format('M Y');

            $salesChartLabels[] = $monthLabel;

            $monthData = $monthlySalesData->firstWhere('month', $monthKey);
            $salesChartValues[] = $monthData ? $monthData->total_sales : 0;
            $ordersChartValues[] = $monthData ? $monthData->total_orders : 0;
        }

        // Order status distribution
        $orderStatusData = SaasOrder::where('seller_id', $sellerId)
            ->select('order_status', DB::raw('COUNT(id) as count'))
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status')
            ->toArray();

        return view('saas_seller.saas_dashboard', compact(
            'sellerProfile',
            'wallet',
            'totalProducts',
            'activeProducts',
            'outOfStockProducts',
            'lowStockProducts',
            'totalOrders',
            'todayOrders',
            'pendingOrders',
            'totalSales',
            'todaySales',
            'thisMonthSales',
            'lastMonthSales',
            'growthPercentage',
            'pendingWithdrawals',
            'totalReviews',
            'averageRating',
            'recentOrders',
            'topProducts',
            'recentReviews',
            'salesChartLabels',
            'salesChartValues',
            'ordersChartValues',
            'orderStatusData'
        ));
    }
}
