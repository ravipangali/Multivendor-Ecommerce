<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SaasOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $status = $request->status;

        $ordersQuery = SaasOrder::with(['customer', 'items'])
            ->where('seller_id', $sellerId);

        if ($status) {
            $ordersQuery->where('order_status', $status);
        }

        $orders = $ordersQuery->latest()->paginate(15);

        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return view('saas_seller.saas_order.saas_index', compact('orders', 'orderStatuses', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasOrder $order)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')->with('error', 'You are not authorized to view this order.');
        }

        $order->load(['customer', 'items.product']);

        return view('saas_seller.saas_order.saas_show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasOrder $order)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')->with('error', 'You are not authorized to edit this order.');
        }

        $order->load(['customer', 'items.product']);

        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return view('saas_seller.saas_order.saas_edit', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasOrder $order)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')->with('error', 'You are not authorized to update this order.');
        }

        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        // Prevent certain status changes based on business rules
        if ($order->order_status === 'delivered' && $request->order_status !== 'refunded') {
            return redirect()->back()->with('error', 'Cannot change status of a delivered order except to refunded.');
        }

        if ($order->order_status === 'refunded') {
            return redirect()->back()->with('error', 'Cannot change status of a refunded order.');
        }

        // Load necessary relationships for email notifications
        $order->load(['customer', 'seller', 'items.product', 'items.productVariation']);

        // Record the previous status for notification purposes
        $previousStatus = $order->order_status;

        $order->order_status = $request->order_status;
        $order->save();

        // If status changed, update order items status as well
        if ($previousStatus !== $request->order_status) {
            SaasOrderItem::where('order_id', $order->id)
                ->update(['status' => $request->order_status]);

            try {
                // Send email to customer
                Mail::to($order->customer->email)->send(
                    new \App\Mail\SaasOrderStatusChanged($order, $previousStatus, 'customer')
                );

                // Send email to seller (optional - since seller is making the change)
                // Mail::to($order->seller->email)->send(
                //     new \App\Mail\SaasOrderStatusChanged($order, $previousStatus, 'seller')
                // );
            } catch (\Exception $e) {
                // Log email error but don't fail the status update
                Log::error('Failed to send order status change emails: ' . $e->getMessage());
            }

            // Log the status change
            Log::info('Seller changed order status', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'previous_status' => $previousStatus,
                'new_status' => $request->order_status,
                'seller_id' => Auth::id(),
            ]);
        }

        return redirect()->route('seller.orders.show', $order->id)->with('success', 'Order status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasOrder $saasOrder)
    {
        //
    }

    /**
     * Display orders by status.
     */
    public function pendingOrders()
    {
        return $this->ordersByStatus('pending', 'Pending Orders');
    }

    public function processingOrders()
    {
        return $this->ordersByStatus('processing', 'Processing Orders');
    }

    public function shippedOrders()
    {
        return $this->ordersByStatus('shipped', 'Shipped Orders');
    }

    public function deliveredOrders()
    {
        return $this->ordersByStatus('delivered', 'Delivered Orders');
    }

    public function cancelledOrders()
    {
        return $this->ordersByStatus('cancelled', 'Cancelled Orders');
    }

    public function refundedOrders()
    {
        return $this->ordersByStatus('refunded', 'Refunded Orders');
    }

    /**
     * Helper method to get orders by status.
     */
    private function ordersByStatus($status, $title)
    {
        $sellerId = Auth::id();

        $orders = SaasOrder::with(['customer', 'items'])
            ->where('seller_id', $sellerId)
            ->where('order_status', $status)
            ->latest()
            ->paginate(15);

        return view('saas_seller.saas_order.saas_status', compact('orders', 'status', 'title'));
    }

    /**
     * Print invoice for the order.
     */
    public function invoice(SaasOrder $order)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')->with('error', 'You are not authorized to access this order invoice.');
        }

        $order->load(['customer', 'items.product']);

        return view('saas_seller.saas_order.saas_invoice', compact('order'));
    }
}
