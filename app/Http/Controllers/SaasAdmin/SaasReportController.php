<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use App\Models\SaasInHouseSale;
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

        // Online sales
        $totalOnlineSales = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // In-house sales
        $totalInHouseSales = SaasInHouseSale::whereBetween('sale_date', [$startDate, $endDate])
            ->sum('total_amount');

        // Combined total sales
        $totalSales = $totalOnlineSales + $totalInHouseSales;

        // Sales by date (combined)
        $startDateOnly = date('Y-m-d', strtotime($startDate));
        $endDateOnly = date('Y-m-d', strtotime($endDate));

        $salesByDate = collect();
        $period = new \DatePeriod(
            new \DateTime($startDateOnly),
            new \DateInterval('P1D'),
            new \DateTime($endDateOnly . ' +1 day')
        );

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            $onlineSales = SaasOrder::whereDate('created_at', $dateString)
                ->where('order_status', '!=', 'cancelled')
                ->sum('total');

            $inHouseSales = SaasInHouseSale::whereDate('sale_date', $dateString)
                ->sum('total_amount');

            $salesByDate->push([
                'date' => $dateString,
                'online_sales' => $onlineSales,
                'in_house_sales' => $inHouseSales,
                'total' => $onlineSales + $inHouseSales
            ]);
        }

        // Sales by payment method (combined)
        $onlinePaymentMethods = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'), DB::raw('"online" as source'))
            ->groupBy('payment_method')
            ->get();

        $inHousePaymentMethods = SaasInHouseSale::whereBetween('sale_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'), DB::raw('"in_house" as source'))
            ->groupBy('payment_method')
            ->get();

        $salesByPaymentMethod = $onlinePaymentMethods->concat($inHousePaymentMethods);

        // Recent orders
        $recentOrders = SaasOrder::with(['customer', 'seller'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        // Recent in-house sales
        $recentInHouseSales = SaasInHouseSale::with(['customer', 'cashier'])
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->latest('sale_date')
            ->limit(10)
            ->get();

        return view('saas_admin.saas_report.saas_sales_report', compact(
            'totalSales',
            'totalOnlineSales',
            'totalInHouseSales',
            'salesByDate',
            'salesByPaymentMethod',
            'recentOrders',
            'recentInHouseSales',
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
     * Display individual seller report.
     */
    public function individualSellerReport(Request $request, $sellerId)
    {
        $seller = User::where('role', 'seller')->findOrFail($sellerId);
        $startDate = $request->start_date ? date('Y-m-d 00:00:00', strtotime($request->start_date)) : date('Y-m-d 00:00:00', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d 23:59:59', strtotime($request->end_date)) : date('Y-m-d 23:59:59');

        // Seller's total sales
        $totalSales = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total');

        // Seller's order count
        $orderCount = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->count();

        // Seller's products count
        $productCount = SaasProduct::where('seller_id', $sellerId)->count();

        // Top selling products by this seller
        $topProducts = DB::table('saas_order_items')
            ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
            ->join('saas_products', 'saas_order_items.product_id', '=', 'saas_products.id')
            ->where('saas_orders.seller_id', $sellerId)
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

        // Recent orders for this seller
        $recentOrders = SaasOrder::with(['customer'])
            ->where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        // Sales by date for this seller
        $salesByDate = SaasOrder::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('saas_admin.saas_report.saas_individual_seller_report', compact(
            'seller',
            'totalSales',
            'orderCount',
            'productCount',
            'topProducts',
            'recentOrders',
            'salesByDate',
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

        // Top customers by online purchase
        $topOnlineCustomers = SaasOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled')
            ->whereNotNull('customer_id')
            ->select('customer_id', DB::raw('SUM(total) as total_spent'), DB::raw('COUNT(*) as order_count'), DB::raw('"online" as source'))
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->with('customer')
            ->get();

        // Top customers by in-house purchases
        $topInHouseCustomers = SaasInHouseSale::whereBetween('sale_date', [$startDate, $endDate])
            ->whereNotNull('customer_id')
            ->select('customer_id', DB::raw('SUM(total_amount) as total_spent'), DB::raw('COUNT(*) as order_count'), DB::raw('"in_house" as source'))
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->with('customer')
            ->get();

        // Combine and sort top customers
        $allCustomerSpending = collect();

        // Process online customers
        foreach ($topOnlineCustomers as $customer) {
            $key = $customer->customer_id;
            if ($allCustomerSpending->has($key)) {
                $existing = $allCustomerSpending->get($key);
                $existing['total_spent'] += $customer->total_spent;
                $existing['online_orders'] = $customer->order_count;
                $existing['online_spent'] = $customer->total_spent;
            } else {
                $allCustomerSpending->put($key, [
                    'customer' => $customer->customer,
                    'total_spent' => $customer->total_spent,
                    'online_orders' => $customer->order_count,
                    'in_house_orders' => 0,
                    'online_spent' => $customer->total_spent,
                    'in_house_spent' => 0
                ]);
            }
        }

        // Process in-house customers
        foreach ($topInHouseCustomers as $customer) {
            $key = $customer->customer_id;
            if ($allCustomerSpending->has($key)) {
                $existing = $allCustomerSpending->get($key);
                $existing['total_spent'] += $customer->total_spent;
                $existing['in_house_orders'] = $customer->order_count;
                $existing['in_house_spent'] = $customer->total_spent;
            } else {
                $allCustomerSpending->put($key, [
                    'customer' => $customer->customer,
                    'total_spent' => $customer->total_spent,
                    'online_orders' => 0,
                    'in_house_orders' => $customer->order_count,
                    'online_spent' => 0,
                    'in_house_spent' => $customer->total_spent
                ]);
            }
        }

        $topCustomers = $allCustomerSpending->sortByDesc('total_spent')->take(10)->values();

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

        // Top selling products from online orders
        $topOnlineProducts = DB::table('saas_order_items')
            ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
            ->join('saas_products', 'saas_order_items.product_id', '=', 'saas_products.id')
            ->whereBetween('saas_orders.created_at', [$startDate, $endDate])
            ->where('saas_orders.order_status', '!=', 'cancelled')
            ->select(
                'saas_products.id',
                'saas_products.name',
                DB::raw('SUM(saas_order_items.quantity) as quantity_sold'),
                DB::raw('SUM(saas_order_items.price * saas_order_items.quantity) as total_sales'),
                DB::raw('"online" as source')
            )
            ->groupBy('saas_products.id', 'saas_products.name')
            ->get();

        // Top selling products from in-house sales
        $topInHouseProducts = DB::table('saas_in_house_sale_items')
            ->join('saas_in_house_sales', 'saas_in_house_sale_items.sale_id', '=', 'saas_in_house_sales.id')
            ->whereBetween('saas_in_house_sales.sale_date', [$startDate, $endDate])
            ->select(
                'saas_in_house_sale_items.product_name as name',
                DB::raw('SUM(saas_in_house_sale_items.quantity) as quantity_sold'),
                DB::raw('SUM(saas_in_house_sale_items.price * saas_in_house_sale_items.quantity) as total_sales'),
                DB::raw('"in_house" as source')
            )
            ->groupBy('saas_in_house_sale_items.product_name')
            ->get();

        // Combine products
        $combinedProducts = collect();

        foreach ($topOnlineProducts as $product) {
            $combinedProducts->put($product->name, [
                'name' => $product->name,
                'online_quantity' => $product->quantity_sold,
                'in_house_quantity' => 0,
                'total_quantity' => $product->quantity_sold,
                'online_sales' => $product->total_sales,
                'in_house_sales' => 0,
                'total_sales' => $product->total_sales
            ]);
        }

        foreach ($topInHouseProducts as $product) {
            if ($combinedProducts->has($product->name)) {
                $existing = $combinedProducts->get($product->name);
                $existing['in_house_quantity'] = $product->quantity_sold;
                $existing['total_quantity'] += $product->quantity_sold;
                $existing['in_house_sales'] = $product->total_sales;
                $existing['total_sales'] += $product->total_sales;
                $combinedProducts->put($product->name, $existing);
            } else {
                $combinedProducts->put($product->name, [
                    'name' => $product->name,
                    'online_quantity' => 0,
                    'in_house_quantity' => $product->quantity_sold,
                    'total_quantity' => $product->quantity_sold,
                    'online_sales' => 0,
                    'in_house_sales' => $product->total_sales,
                    'total_sales' => $product->total_sales
                ]);
            }
        }

        $topProducts = $combinedProducts->sortByDesc('total_quantity')->take(10)->values();

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
