<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use App\Models\SaasProductReview;
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

        // Total products
        $totalProducts = SaasProduct::where('seller_id', $sellerId)->count();

        // Active products
        $activeProducts = SaasProduct::where('seller_id', $sellerId)
            ->where('is_active', true)
            ->count();

        // Total orders
        $totalOrders = SaasOrder::where('seller_id', $sellerId)->count();

        // Total sales
        $totalSales = SaasOrder::where('seller_id', $sellerId)
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Recent orders (last 5)
        $recentOrders = SaasOrder::where('seller_id', $sellerId)
            ->with('customer')
            ->latest()
            ->limit(5)
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
                DB::raw('SUM(saas_order_items.quantity) as quantity_sold'),
                DB::raw('SUM(saas_order_items.price * saas_order_items.quantity) as total_sales')
            )
            ->groupBy('saas_products.id', 'saas_products.name')
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

        // Monthly sales chart data (last 6 months)
        $monthlySales = SaasOrder::where('seller_id', $sellerId)
            ->where('order_status', '!=', 'cancelled')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total_sales')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        return view('saas_seller.saas_dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'totalSales',
            'recentOrders',
            'topProducts',
            'recentReviews',
            'monthlySales'
        ));
    }

    /**
     * Display the seller's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            return redirect()->route('seller.profile.create');
        }

        return view('saas_seller.saas_profile.saas_show', compact('user', 'sellerProfile'));
    }

    /**
     * Show the form for creating a seller profile.
     */
    public function createProfile()
    {
        $user = Auth::user();

        if ($user->sellerProfile) {
            return redirect()->route('seller.profile');
        }

        return view('saas_seller.saas_profile.saas_create', compact('user'));
    }

    /**
     * Display the seller's store settings.
     */
    public function storeSettings()
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            return redirect()->route('seller.profile.create');
        }

        return view('saas_seller.saas_settings.saas_store', compact('user', 'sellerProfile'));
    }
}
