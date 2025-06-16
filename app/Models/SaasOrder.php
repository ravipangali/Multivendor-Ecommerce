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
        'order_notes',
        'placed_at',
        // Individual shipping address fields
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_country',
        'shipping_street_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        // Individual billing address fields
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_country',
        'billing_street_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        // Coupon tracking
        'coupon_code',
        'coupon_discount_amount',
        'coupon_discount_type',
        // Cancellation and admin fields
        'cancelled_at',
        'cancellation_reason',
        'admin_note',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Payment status constants
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';
    const PAYMENT_STATUS_CANCELED = 'canceled';

    public static function getPaymentStatuses()
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'Pending',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_FAILED => 'Failed',
            self::PAYMENT_STATUS_REFUNDED => 'Refunded',
            self::PAYMENT_STATUS_CANCELED => 'Canceled',
        ];
    }

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
            return $item->tax ?? 0;
        });

        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $subtotal + $tax + ($this->shipping_fee ?? 0) - ($this->discount ?? 0);

        return $this;
    }

    /**
     * Get tax amount with fallback
     */
    public function getTaxAmountAttribute()
    {
        return $this->tax ?? 0;
    }

    /**
     * Get shipping fee with fallback
     */
    public function getShippingFeeAmountAttribute()
    {
        return $this->shipping_fee ?? 0;
    }

    /**
     * Get discount amount with fallback
     */
    public function getDiscountAmountAttribute()
    {
        return $this->discount ?? 0;
    }

    /**
     * Get order total with proper calculation
     */
    public function getTotalAmountAttribute()
    {
        return ($this->subtotal ?? 0) + ($this->tax ?? 0) + ($this->shipping_fee ?? 0) - ($this->discount ?? 0);
    }

    /**
     * Check if order has tax applied
     */
    public function hasTax()
    {
        return ($this->tax ?? 0) > 0;
    }

    /**
     * Get tax percentage based on subtotal
     */
    public function getTaxPercentageAttribute()
    {
        if (!$this->subtotal || $this->subtotal <= 0) {
            return 0;
        }

        return round((($this->tax ?? 0) / $this->subtotal) * 100, 2);
    }

    /**
     * Get formatted tax display
     */
    public function getFormattedTaxAttribute()
    {
        $taxAmount = $this->tax ?? 0;
        if ($taxAmount <= 0) {
            return 'Tax: Rs. 0.00';
        }

        $taxPercentage = $this->tax_percentage;
        return "Tax ({$taxPercentage}%): Rs. " . number_format($taxAmount, 2);
    }

    /**
     * Check if order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    /**
     * Check if payment is pending.
     */
    public function isPaymentPending()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PENDING;
    }

    /**
     * Check if payment is refunded.
     */
    public function isPaymentRefunded()
    {
        return $this->payment_status === self::PAYMENT_STATUS_REFUNDED;
    }

    /**
     * Check if payment is canceled.
     */
    public function isPaymentCanceled()
    {
        return $this->payment_status === self::PAYMENT_STATUS_CANCELED;
    }

    /**
     * Check if digital products in this order can be downloaded.
     */
    public function canDownloadDigitalProducts()
    {
        return $this->isDelivered() && $this->isPaid();
    }

    /**
     * Get downloadable digital products from this order.
     */
    public function getDownloadableDigitalProducts()
    {
        if (!$this->canDownloadDigitalProducts()) {
            return collect();
        }

        return $this->items()->with('product')
            ->whereHas('product', function ($query) {
                $query->where('product_type', 'Digital')
                      ->whereNotNull('file');
            })
            ->get()
            ->pluck('product')
            ->unique('id');
    }

    /**
     * Check if order has digital products.
     */
    public function hasDigitalProducts()
    {
        return $this->items()->whereHas('product', function ($query) {
            $query->where('product_type', 'Digital');
        })->exists();
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

    /**
     * Get the coupon used for this order.
     */
    public function coupon()
    {
        return $this->belongsTo(SaasCoupon::class, 'coupon_code', 'code');
    }

    /**
     * Check if order has a coupon applied
     */
    public function hasCoupon()
    {
        return !empty($this->coupon_code) && ($this->coupon_discount_amount ?? 0) > 0;
    }

    /**
     * Get formatted coupon discount amount
     */
    public function getFormattedCouponDiscountAttribute()
    {
        if (!$this->hasCoupon()) {
            return 'Rs. 0.00';
        }

        return 'Rs. ' . number_format($this->coupon_discount_amount, 2);
    }

    /**
     * Get coupon discount percentage
     */
    public function getCouponDiscountPercentageAttribute()
    {
        if (!$this->hasCoupon() || ($this->subtotal ?? 0) <= 0) {
            return 0;
        }

        return round(($this->coupon_discount_amount / $this->subtotal) * 100, 2);
    }

    /**
     * Get formatted coupon details for display
     */
    public function getFormattedCouponDetailsAttribute()
    {
        if (!$this->hasCoupon()) {
            return null;
        }

        $details = [
            'code' => $this->coupon_code,
            'discount_amount' => $this->coupon_discount_amount,
            'discount_type' => $this->coupon_discount_type,
            'formatted_amount' => $this->formatted_coupon_discount,
            'percentage' => $this->coupon_discount_percentage,
        ];

        if ($this->coupon_discount_type === 'percentage') {
            $details['display'] = $this->coupon_discount_percentage . '% off';
        } else {
            $details['display'] = 'Rs. ' . number_format($this->coupon_discount_amount, 2) . ' off';
        }

        return $details;
    }

    /**
     * Get formatted shipping address.
     */
    public function getFormattedShippingAddressAttribute()
    {
        $address = [];
        if ($this->shipping_name) $address[] = $this->shipping_name;
        if ($this->shipping_email) $address[] = $this->shipping_email;
        if ($this->shipping_phone) $address[] = $this->shipping_phone;
        if ($this->shipping_street_address) $address[] = $this->shipping_street_address;
        if ($this->shipping_city) $address[] = $this->shipping_city;
        if ($this->shipping_state) $address[] = $this->shipping_state;
        if ($this->shipping_postal_code) $address[] = $this->shipping_postal_code;
        if ($this->shipping_country) $address[] = $this->shipping_country;

        return implode(', ', $address);
    }

    /**
     * Get formatted billing address.
     */
    public function getFormattedBillingAddressAttribute()
    {
        $address = [];
        if ($this->billing_name) $address[] = $this->billing_name;
        if ($this->billing_email) $address[] = $this->billing_email;
        if ($this->billing_phone) $address[] = $this->billing_phone;
        if ($this->billing_street_address) $address[] = $this->billing_street_address;
        if ($this->billing_city) $address[] = $this->billing_city;
        if ($this->billing_state) $address[] = $this->billing_state;
        if ($this->billing_postal_code) $address[] = $this->billing_postal_code;
        if ($this->billing_country) $address[] = $this->billing_country;

        return implode(', ', $address);
    }
}
