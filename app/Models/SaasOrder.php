<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'seller_id',
        'total',
        'subtotal',
        'discount',
        'tax',
        'shipping_fee',
        'payment_status',
        'order_status',
        'payment_method',
        'shipping_address',
        'billing_address',
        'placed_at',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
    ];

    /**
     * Get the customer that placed the order.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the seller that fulfilled the order.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(SaasOrderItem::class, 'order_id');
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $date = now()->format('Ymd');
        $random = mt_rand(1000, 9999);

        return $prefix . $date . '-' . $random;
    }

    /**
     * Calculate order totals.
     */
    public function calculateTotals()
    {
        $items = $this->items;

        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $tax = $items->sum(function ($item) {
            return $item->tax;
        });

        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $subtotal + $tax + $this->shipping_fee - $this->discount;

        return $this;
    }

    /**
     * Check if order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if order is pending.
     */
    public function isPending()
    {
        return $this->order_status === 'pending';
    }

    /**
     * Check if order is processing.
     */
    public function isProcessing()
    {
        return $this->order_status === 'processing';
    }

    /**
     * Check if order is shipped.
     */
    public function isShipped()
    {
        return $this->order_status === 'shipped';
    }

    /**
     * Check if order is delivered.
     */
    public function isDelivered()
    {
        return $this->order_status === 'delivered';
    }

    /**
     * Check if order is cancelled.
     */
    public function isCancelled()
    {
        return $this->order_status === 'cancelled';
    }

    /**
     * Check if order is refunded.
     */
    public function isRefunded()
    {
        return $this->order_status === 'refunded';
    }
}
