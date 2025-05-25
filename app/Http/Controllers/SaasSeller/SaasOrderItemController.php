<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasOrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SaasOrder $order)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'You are not authorized to view order items for this order.');
        }

        $items = $order->items()->with('product')->get();
        return view('saas_seller.saas_order_item.saas_index', compact('order', 'items'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasOrder $order, SaasOrderItem $item)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'You are not authorized to view this order item.');
        }

        // Check if the item belongs to this order
        if ($item->order_id !== $order->id) {
            return redirect()->route('seller.orders.items.index', $order->id)
                ->with('error', 'This item does not belong to the specified order.');
        }

        $item->load(['product', 'variation']);
        return view('saas_seller.saas_order_item.saas_show', compact('order', 'item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasOrder $order, SaasOrderItem $item)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'You are not authorized to edit this order item.');
        }

        // Check if the item belongs to this order
        if ($item->order_id !== $order->id) {
            return redirect()->route('seller.orders.items.index', $order->id)
                ->with('error', 'This item does not belong to the specified order.');
        }

        // Only allow editing if the order is still pending or processing
        if (!in_array($order->order_status, ['pending', 'processing'])) {
            return redirect()->route('seller.orders.items.show', [$order->id, $item->id])
                ->with('error', 'Cannot edit items for orders that are not in pending or processing status.');
        }

        $item->load(['product', 'variation']);

        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return view('saas_seller.saas_order_item.saas_edit', compact('order', 'item', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasOrder $order, SaasOrderItem $item)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'You are not authorized to update this order item.');
        }

        // Check if the item belongs to this order
        if ($item->order_id !== $order->id) {
            return redirect()->route('seller.orders.items.index', $order->id)
                ->with('error', 'This item does not belong to the specified order.');
        }

        // Only allow updates if the order is still pending or processing
        if (!in_array($order->order_status, ['pending', 'processing'])) {
            return redirect()->route('seller.orders.items.show', [$order->id, $item->id])
                ->with('error', 'Cannot update items for orders that are not in pending or processing status.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        // Update the item status
        $item->status = $request->status;
        $item->save();

        // Check if all items have the same status, if so, update the order status
        $allItemsHaveSameStatus = SaasOrderItem::where('order_id', $order->id)
            ->where('status', '!=', $request->status)
            ->count() === 0;

        if ($allItemsHaveSameStatus) {
            $order->order_status = $request->status;
            $order->save();
        }

        return redirect()->route('seller.orders.items.index', $order->id)
            ->with('success', 'Order item status updated successfully');
    }

    /**
     * Update status for a batch of order items.
     */
    public function updateStatus(Request $request, SaasOrder $order)
    {
        // Check if the order belongs to the authenticated seller
        if ($order->seller_id !== Auth::id()) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'You are not authorized to update items for this order.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:saas_order_items,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        // Only allow updates if the order is still pending or processing
        if (!in_array($order->order_status, ['pending', 'processing'])) {
            return redirect()->route('seller.orders.items.index', $order->id)
                ->with('error', 'Cannot update items for orders that are not in pending or processing status.');
        }

        // Update the selected items
        SaasOrderItem::whereIn('id', $request->items)
            ->where('order_id', $order->id)
            ->update(['status' => $request->status]);

        // Check if all items have the same status, if so, update the order status
        $allItemsHaveSameStatus = SaasOrderItem::where('order_id', $order->id)
            ->where('status', '!=', $request->status)
            ->count() === 0;

        if ($allItemsHaveSameStatus) {
            $order->order_status = $request->status;
            $order->save();
        }

        return redirect()->route('seller.orders.items.index', $order->id)
            ->with('success', 'Selected order items status updated successfully');
    }
}
