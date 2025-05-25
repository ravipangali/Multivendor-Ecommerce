<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaasAdminDashboardController extends Controller
{
    public function index()
    {
        // Get current date
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Total Orders
        $totalOrders = SaasOrder::count();
        $recentOrders = SaasOrder::whereDate('created_at', $today)->count();

        // Total Sales
        $totalSales = SaasOrder::where('order_status', '!=', 'cancelled')
                      ->sum('total');
        $recentSales = SaasOrder::whereDate('created_at', $today)
                      ->where('order_status', '!=', 'cancelled')
                      ->sum('total');

        // Products
        $totalProducts = SaasProduct::count();
        $lowStockProducts = SaasProduct::where('stock', '<', 10)
                           ->where('is_active', true)
                           ->count();

        // Customers
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomers = User::where('role', 'customer')
                     ->whereDate('created_at', $today)
                     ->count();

        // Latest Orders
        $latestOrders = SaasOrder::with(['customer'])
                      ->latest()
                      ->take(10)
                      ->get();

        // Sales Chart Data (Last 7 days)
        $salesChart = SaasOrder::where('created_at', '>=', Carbon::now()->subDays(7))
                    ->where('order_status', '!=', 'cancelled')
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

        $salesChartLabels = $salesChart->pluck('date')->toArray();
        $salesChartValues = $salesChart->pluck('total')->toArray();

        // Top Products
        $topProducts = DB::table('saas_order_items')
                     ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
                     ->join('saas_products', 'saas_order_items.product_id', '=', 'saas_products.id')
                     ->where('saas_orders.created_at', '>=', Carbon::now()->subDays(30))
                     ->where('saas_orders.order_status', '!=', 'cancelled')
                     ->select(
                        'saas_products.id',
                        'saas_products.name',
                        DB::raw('SUM(saas_order_items.quantity) as quantity_sold')
                     )
                     ->groupBy('saas_products.id', 'saas_products.name')
                     ->orderBy('quantity_sold', 'desc')
                     ->limit(5)
                     ->get();

        return view('saas_admin.saas_dashboard', compact(
            'totalOrders',
            'recentOrders',
            'totalSales',
            'recentSales',
            'totalProducts',
            'lowStockProducts',
            'totalCustomers',
            'newCustomers',
            'latestOrders',
            'salesChartLabels',
            'salesChartValues',
            'topProducts'
        ));
    }
}
