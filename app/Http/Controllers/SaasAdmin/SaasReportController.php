<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaasReportController extends Controller
{
    /**
     * Display sales report.
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Total sales
        $totalSales = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Sales by date
        $salesByDate = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Sales by payment method
        $salesByPaymentMethod = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Recent orders
        $recentOrders = SaasOrder::with(['customer', 'seller'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        return view('saas_admin.saas_report.saas_sales_report', compact(
            'totalSales',
            'salesByDate',
            'salesByPaymentMethod',
            'recentOrders',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display seller report.
     */
    public function sellerReport(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Top sellers by sales
        $topSellers = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('seller_id', DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('seller_id')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->with('seller')
            ->get();

        // Top sellers by product count
        $topSellersByProducts = User::where('role', 'seller')
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(10)
            ->get();

        // New sellers
        $newSellers = User::where('role', 'seller')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        return view('saas_admin.saas_report.saas_seller_report', compact(
            'topSellers',
            'topSellersByProducts',
            'newSellers',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display customer report.
     */
    public function customerReport(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Top customers by purchase
        $topCustomers = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('customer_id', DB::raw('SUM(total) as total_spent'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->with('customer')
            ->get();

        // New customers
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        // Customer count by date
        $customersByDate = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('saas_admin.saas_report.saas_customer_report', compact(
            'topCustomers',
            'newCustomers',
            'customersByDate',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display product report.
     */
    public function productReport(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Top selling products
        $topProducts = DB::table('saas_order_items')
            ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
            ->join('saas_products', 'saas_order_items.product_id', '=', 'saas_products.id')
            ->whereBetween('saas_orders.created_at', [$startDate, $endDate])
            ->where('saas_orders.order_status', '!=', 'cancelled')
            ->select(
                'saas_products.id',
                'saas_products.name',
                DB::raw('SUM(saas_order_items.quantity) as quantity_sold'),
                DB::raw('SUM(saas_order_items.price * saas_order_items.quantity) as total_sales')
            )
            ->groupBy('saas_products.id', 'saas_products.name')
            ->orderBy('quantity_sold', 'desc')
            ->limit(10)
            ->get();

        // Low stock products
        $lowStockProducts = SaasProduct::where('stock', '<', 10)
            ->where('is_active', true)
            ->with(['category', 'seller'])
            ->limit(10)
            ->get();

        // Products by category
        $productsByCategory = DB::table('saas_products')
            ->join('saas_categories', 'saas_products.category_id', '=', 'saas_categories.id')
            ->select('saas_categories.name', DB::raw('COUNT(*) as product_count'))
            ->groupBy('saas_categories.name')
            ->orderBy('product_count', 'desc')
            ->get();

        return view('saas_admin.saas_report.saas_product_report', compact(
            'topProducts',
            'lowStockProducts',
            'productsByCategory',
            'startDate',
            'endDate'
        ));
    }
}
