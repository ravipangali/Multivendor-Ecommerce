<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use Illuminate\Http\Request;

class SaasOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = SaasOrder::with(['customer', 'seller'])->latest()->paginate(15);
        return view('saas_admin.saas_order.saas_index', compact('orders'));
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
        $order->load(['customer', 'seller', 'items.product']);
        return view('saas_admin.saas_order.saas_show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasOrder $order)
    {
        $order->load(['customer', 'seller', 'items.product']);
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];

        return view('saas_admin.saas_order.saas_edit', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasOrder $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        // Record the previous status for notification purposes
        $previousStatus = $order->order_status;

        $order->order_status = $request->order_status;
        $order->save();

        // If status changed, send notification
        if ($previousStatus != $request->order_status) {
            // Send notification to customer about status change
            // This would typically be handled by a notification class
            // Notification::send($order->customer, new OrderStatusChanged($order));
        }

        toast('Order status updated successfully', 'success');
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
