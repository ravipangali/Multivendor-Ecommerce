<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use App\Models\SaasInHouseSale;
use App\Models\SaasSetting;
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

        // Admin Balance
        $adminBalance = SaasSetting::get('balance', 0);

        // Total Orders (Online)
        $totalOrders = SaasOrder::count();
        $recentOrders = SaasOrder::whereDate('created_at', $today)->count();

        // Total Sales (Online)
        $totalSales = SaasOrder::where('order_status', '!=', 'cancelled')
                      ->sum('total');
        $recentSales = SaasOrder::whereDate('created_at', $today)
                      ->where('order_status', '!=', 'cancelled')
                      ->sum('total');

        // In House Sales
        $totalInHouseSales = SaasInHouseSale::count();
        $todayInHouseSales = SaasInHouseSale::whereDate('sale_date', $today)->count();
        $totalInHouseRevenue = SaasInHouseSale::sum('total_amount');
        $todayInHouseRevenue = SaasInHouseSale::whereDate('sale_date', $today)->sum('total_amount');

        // Combined Sales Revenue
        $totalCombinedRevenue = $totalSales + $totalInHouseRevenue;
        $todayCombinedRevenue = $recentSales + $todayInHouseRevenue;

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

        // Recent In House Sales
        $recentInHouseSales = SaasInHouseSale::with(['customer', 'cashier'])
                            ->latest('sale_date')
                            ->take(5)
                            ->get();

        // Sales Chart Data (Last 7 days) - Combined online and in-house
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $onlineSales = SaasOrder::whereDate('created_at', $date)
                         ->where('order_status', '!=', 'cancelled')
                         ->sum('total');

            $inHouseSales = SaasInHouseSale::whereDate('sale_date', $date)
                          ->sum('total_amount');

            $last7Days->push([
                'date' => $date,
                'online' => $onlineSales,
                'in_house' => $inHouseSales,
                'total' => $onlineSales + $inHouseSales
            ]);
        }

        $salesChartLabels = $last7Days->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('M d');
        })->toArray();
        $salesChartOnlineValues = $last7Days->pluck('online')->toArray();
        $salesChartInHouseValues = $last7Days->pluck('in_house')->toArray();
        $salesChartTotalValues = $last7Days->pluck('total')->toArray();

        // Top Products (Combined from online orders and in-house sales)
        $topOnlineProducts = DB::table('saas_order_items')
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
                     ->get();

        $topInHouseProducts = DB::table('saas_in_house_sale_items')
                     ->join('saas_in_house_sales', 'saas_in_house_sale_items.sale_id', '=', 'saas_in_house_sales.id')
                     ->where('saas_in_house_sales.sale_date', '>=', Carbon::now()->subDays(30))
                     ->select(
                        'saas_in_house_sale_items.product_name as name',
                        DB::raw('SUM(saas_in_house_sale_items.quantity) as quantity_sold')
                     )
                     ->groupBy('saas_in_house_sale_items.product_name')
                     ->get();

        // Combine and merge product sales
        $combinedProducts = collect();

        foreach ($topOnlineProducts as $product) {
            $combinedProducts->put($product->name, $product->quantity_sold);
        }

        foreach ($topInHouseProducts as $product) {
            $existing = $combinedProducts->get($product->name, 0);
            $combinedProducts->put($product->name, $existing + $product->quantity_sold);
        }

        $topProducts = $combinedProducts->sortDesc()->take(5)->map(function($quantity, $name) {
            return (object)[
                'name' => $name,
                'quantity_sold' => $quantity
            ];
        })->values();

        return view('saas_admin.saas_dashboard', compact(
            'totalOrders',
            'recentOrders',
            'totalSales',
            'recentSales',
            'totalInHouseSales',
            'todayInHouseSales',
            'totalInHouseRevenue',
            'todayInHouseRevenue',
            'totalCombinedRevenue',
            'todayCombinedRevenue',
            'totalProducts',
            'lowStockProducts',
            'totalCustomers',
            'newCustomers',
            'latestOrders',
            'recentInHouseSales',
            'salesChartLabels',
            'salesChartOnlineValues',
            'salesChartInHouseValues',
            'salesChartTotalValues',
            'topProducts',
            'adminBalance'
        ));
    }
}
