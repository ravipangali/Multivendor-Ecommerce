<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaasReportController extends Controller
{
    /**
     * Display sales report.
     */
    public function salesReport(Request $request)
    {
        $sellerId = Auth::id();
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Total sales
        $totalSales = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Sales by date
        $salesByDate = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Sales by payment method
        $salesByPaymentMethod = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Recent orders
        $recentOrders = SaasOrder::where('seller_id', $sellerId)
            ->with('customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        return view('saas_seller.saas_report.saas_sales_report', compact(
            'totalSales',
            'salesByDate',
            'salesByPaymentMethod',
            'recentOrders',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display product report.
     */
    public function productReport(Request $request)
    {
        $sellerId = Auth::id();
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Top selling products
        $topProducts = DB::table('saas_order_items')
            ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
            ->join('saas_products', 'saas_order_items.product_id', '=', 'saas_products.id')
            ->where('saas_products.seller_id', $sellerId)
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
        $lowStockProducts = SaasProduct::where('seller_id', $sellerId)
            ->where('stock', '<', 10)
            ->where('is_active', true)
            ->with('category')
            ->limit(10)
            ->get();

        // Products by category
        $productsByCategory = DB::table('saas_products')
            ->join('saas_categories', 'saas_products.category_id', '=', 'saas_categories.id')
            ->where('saas_products.seller_id', $sellerId)
            ->select('saas_categories.name', DB::raw('COUNT(*) as product_count'))
            ->groupBy('saas_categories.name')
            ->orderBy('product_count', 'desc')
            ->get();

        return view('saas_seller.saas_report.saas_product_report', compact(
            'topProducts',
            'lowStockProducts',
            'productsByCategory',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display customer report.
     */
    public function customerReport(Request $request)
    {
        $sellerId = Auth::id();
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Top customers by purchase
        $topCustomers = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('customer_id', DB::raw('SUM(total) as total_spent'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->with('customer')
            ->get();

        // Repeat customers
        $repeatCustomers = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('customer_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_id')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->orderBy('order_count', 'desc')
            ->limit(10)
            ->with('customer')
            ->get();

        return view('saas_seller.saas_report.saas_customer_report', compact(
            'topCustomers',
            'repeatCustomers',
            'startDate',
            'endDate'
        ));
    }
}
