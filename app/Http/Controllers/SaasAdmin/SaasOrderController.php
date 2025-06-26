<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Services\SaasCouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SaasOrderController extends Controller
{
    protected $couponService;

    public function __construct(SaasCouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaasOrder::with(['customer', 'seller']);

        // Filter by coupon code if provided
        if ($request->has('coupon_code') && !empty($request->coupon_code)) {
            $query->where('coupon_code', $request->coupon_code);
        }

        // Filter by order status if provided
        if ($request->has('order_status') && !empty($request->order_status)) {
            $query->where('order_status', $request->order_status);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        // Get filter options for dropdowns
        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return view('saas_admin.saas_order.saas_index', compact('orders', 'orderStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Usually orders are created through checkout process, not admin panel
        toast('Orders are typically created through the checkout process', 'info');
        return redirect()->route('admin.orders.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Usually orders are created through checkout process, not admin panel
        toast('Orders are typically created through the checkout process', 'info');
        return redirect()->route('admin.orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasOrder $order)
    {
        $order->load(['customer', 'seller', 'items.product.images', 'items.productVariation.attribute', 'items.productVariation.attributeValue']);

        // Ensure order calculations are up to date
        $order->calculateTotals();

        // Get comprehensive coupon data if order has a coupon
        $couponData = null;
        if ($order->hasCoupon()) {
            $couponData = $this->couponService->getCouponDetailsForOrder($order);
        }

        return view('saas_admin.saas_order.saas_show', compact('order', 'couponData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasOrder $order)
    {
        $order->load(['customer', 'seller', 'items.product']);
        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return view('saas_admin.saas_order.saas_edit', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasOrder $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded,canceled',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        // Load necessary relationships for email notifications
        $order->load(['customer', 'seller', 'items.product', 'items.productVariation']);

        // Record the previous statuses for notification purposes
        $previousOrderStatus = $order->order_status;
        $previousPaymentStatus = $order->payment_status;

        $order->order_status = $request->order_status;
        $order->payment_status = $request->payment_status;

        // Update admin note if provided
        if ($request->has('admin_note')) {
            $order->admin_note = $request->admin_note;
        }

        $order->save();

        // If status changed, send notification
        if ($previousOrderStatus != $request->order_status || $previousPaymentStatus != $request->payment_status) {
            try {
                // Send email to customer
                Mail::to($order->customer->email)->send(
                    new \App\Mail\SaasOrderStatusChanged($order, $previousOrderStatus, 'customer')
                );

                // Send email to seller
                if ($order->seller && $order->seller->email) {
                    Mail::to($order->seller->email)->send(
                        new \App\Mail\SaasOrderStatusChanged($order, $previousOrderStatus, 'seller')
                    );
                }

                // Send email to admin
                $adminEmail = config('app.admin_email', 'admin@example.com');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(
                        new \App\Mail\SaasOrderStatusChanged($order, $previousOrderStatus, 'admin')
                    );
                }

                Log::info('Order status change emails sent successfully', [
                    'order_id' => $order->id,
                    'customer_email' => $order->customer->email,
                    'seller_email' => $order->seller->email ?? 'N/A',
                    'admin_email' => $adminEmail
                ]);

            } catch (\Exception $e) {
                // Log email error but don't fail the status update
                Log::error('Failed to send order status change emails', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Log the status change
            Log::info('Order status or payment status changed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'previous_order_status' => $previousOrderStatus,
                'new_order_status' => $request->order_status,
                'previous_payment_status' => $previousPaymentStatus,
                'new_payment_status' => $request->payment_status,
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
            ]);
        }

        toast('Order updated successfully', 'success');
        return redirect()->route('admin.orders.show', $order->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasOrder $order)
    {
        // In e-commerce, orders should not typically be deleted but archived/cancelled
        toast('Orders cannot be deleted for record keeping purposes. Please mark as cancelled instead.', 'error');
        return redirect()->route('admin.orders.index');
    }
}
