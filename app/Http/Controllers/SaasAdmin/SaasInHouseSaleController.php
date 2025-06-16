<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasInHouseSale;
use App\Models\SaasInHouseSaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SaasInHouseSaleController extends Controller
{
    /**
     * Display a listing of in-house sales
     */
    public function index(Request $request)
    {
        $query = SaasInHouseSale::with(['cashier', 'saleItems'])
            ->orderBy('sale_date', 'desc');

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->paymentStatus($request->payment_status);
        }

        // Search by sale number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $sales = $query->paginate(20);

        // Calculate summary statistics
        $totalSales = SaasInHouseSale::sum('total_amount');
        $todaySales = SaasInHouseSale::whereDate('sale_date', today())->sum('total_amount');
        $pendingPayments = SaasInHouseSale::where('payment_status', 'pending')->sum('due_amount');

        return view('saas_admin.saas_in_house_sales.saas_index', compact(
            'sales', 'totalSales', 'todaySales', 'pendingPayments'
        ));
    }

    /**
     * Display the specified sale
     */
    public function show(SaasInHouseSale $sale)
    {
        $sale->load(['cashier', 'saleItems.product', 'saleItems.variation']);

        return view('saas_admin.saas_in_house_sales.saas_show', compact('sale'));
    }

    /**
     * Generate sales report
     */
    public function report(Request $request)
    {
        try {
            // Handle both parameter formats for backward compatibility
            $startDate = $request->get('start_date') ?? $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date') ?? $request->get('date_to', now()->format('Y-m-d'));

            // Ensure dates are in correct format
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));

            // Debug: Log the query parameters
            Log::info('In-House Sales Report Query', [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_sales_count' => SaasInHouseSale::count(),
                'sales_in_range_count' => SaasInHouseSale::whereDate('sale_date', '>=', $startDate)
                    ->whereDate('sale_date', '<=', $endDate)->count()
            ]);

            // Get sales with date range
            $sales = SaasInHouseSale::with(['cashier', 'saleItems.product'])
                ->whereDate('sale_date', '>=', $startDate)
                ->whereDate('sale_date', '<=', $endDate)
                ->orderBy('sale_date', 'desc')
                ->get();

            // Calculate totals
            $totalRevenue = $sales->sum('total_amount');
            $totalDiscount = $sales->sum('discount_amount');
            $totalTax = $sales->sum('tax_amount');
            $totalSalesCount = $sales->count();

            // Calculate total items sold from sale items
            $totalItemsSold = 0;
            foreach ($sales as $sale) {
                $totalItemsSold += $sale->saleItems->sum('quantity');
            }

            $avgSaleValue = $totalSalesCount > 0 ? $totalRevenue / $totalSalesCount : 0;

            // Top selling products - Updated query to handle date range properly
            $topProducts = SaasInHouseSaleItem::select('product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_revenue'))
                ->whereHas('sale', function($query) use ($startDate, $endDate) {
                    $query->whereDate('sale_date', '>=', $startDate)
                          ->whereDate('sale_date', '<=', $endDate);
                })
                ->groupBy('product_name')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get();

            // Sales by cashier
            $salesByCashier = $sales->groupBy('cashier_id')->map(function($group) {
                $cashier = $group->first()->cashier;
                return (object)[
                    'cashier_name' => $cashier ? $cashier->name : 'Unknown',
                    'sales_count' => $group->count(),
                    'total_revenue' => $group->sum('total_amount')
                ];
            })->values();

            // Daily breakdown - Fixed to handle collections properly
            $dailySales = $sales->groupBy(function($sale) {
                return $sale->sale_date->format('Y-m-d');
            })->map(function($group, $date) {
                $revenue = $group->sum('total_amount');
                $count = $group->count();

                // Calculate total items for this day
                $totalItems = 0;
                foreach ($group as $sale) {
                    $totalItems += $sale->saleItems->sum('quantity');
                }

                return (object)[
                    'date' => $date,
                    'sales_count' => $count,
                    'total_items' => $totalItems,
                    'total_revenue' => $revenue,
                    'avg_sale_value' => $count > 0 ? $revenue / $count : 0
                ];
            })->sortBy('date')->values();

            // Group by payment method
            $paymentMethods = $sales->groupBy('payment_method')->map(function($group, $method) {
                return (object)[
                    'payment_method' => ucfirst(str_replace('_', ' ', $method)),
                    'count' => $group->count(),
                    'amount' => $group->sum('total_amount')
                ];
            })->values();

            // Prepare analytics array for the view
            $analytics = [
                'total_sales' => $totalSalesCount,
                'total_revenue' => $totalRevenue,
                'total_discount' => $totalDiscount,
                'total_tax' => $totalTax,
                'avg_sale_value' => $avgSaleValue,
                'total_items_sold' => $totalItemsSold,
                'top_products' => $topProducts,
                'sales_by_cashier' => $salesByCashier,
                'daily_sales' => $dailySales,
                'payment_methods' => $paymentMethods
            ];

            // Debug: Log analytics summary
            \Log::info('In-House Sales Report Analytics', [
                'total_sales' => $totalSalesCount,
                'total_revenue' => $totalRevenue,
                'date_range' => "{$startDate} to {$endDate}",
                'has_data' => $totalSalesCount > 0
            ]);

            return view('saas_admin.saas_in_house_sales.saas_reports', compact(
                'analytics', 'startDate', 'endDate', 'sales'
            ));

        } catch (\Exception $e) {
            \Log::error('In-House Sales Report Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all()
            ]);

            toast('Error generating report: ' . $e->getMessage(), 'error');
            return redirect()->route('admin.in-house-sales.index');
        }
    }

    /**
     * Debug method to check sales data
     */
    public function debugSalesData()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $totalSales = SaasInHouseSale::count();
        $salesWithItems = SaasInHouseSale::has('saleItems')->count();
        $recentSales = SaasInHouseSale::with(['saleItems', 'cashier'])
            ->latest('sale_date')
            ->limit(5)
            ->get();

        $sampleData = [
            'total_sales' => $totalSales,
            'sales_with_items' => $salesWithItems,
            'recent_sales' => $recentSales->map(function($sale) {
                return [
                    'id' => $sale->id,
                    'sale_number' => $sale->sale_number,
                    'sale_date' => $sale->sale_date,
                    'total_amount' => $sale->total_amount,
                    'items_count' => $sale->saleItems->count(),
                    'cashier' => $sale->cashier ? $sale->cashier->name : 'No Cashier'
                ];
            }),
            'database_tables' => [
                'saas_in_house_sales_exists' => \Schema::hasTable('saas_in_house_sales'),
                'saas_in_house_sale_items_exists' => \Schema::hasTable('saas_in_house_sale_items'),
            ]
        ];

        return response()->json($sampleData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, SaasInHouseSale $sale)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,pending,partial',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $sale->update([
            'payment_status' => $request->payment_status,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $sale->total_amount - $request->paid_amount,
        ]);

        toast('Payment status updated successfully', 'success');
        return redirect()->back();
    }

    /**
     * Delete a sale
     */
    public function destroy(SaasInHouseSale $sale)
    {
        $sale->delete();

        toast('Sale deleted successfully', 'success');
        return redirect()->route('admin.in-house-sales.index');
    }

    /**
     * Print receipt
     */
    public function printReceipt(SaasInHouseSale $sale)
    {
        $sale->load(['cashier', 'saleItems.product']);

        return view('saas_admin.saas_in_house_sales.saas_receipt', compact('sale'));
    }
}
