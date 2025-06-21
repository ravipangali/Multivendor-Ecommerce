<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaasInHouseSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'customer_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'discount_type',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'notes',
        'cashier_id',
        'sale_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    /**
     * Generate a unique sale number
     */
    public static function generateSaleNumber()
    {
        $prefix = 'IHS';
        $date = now()->format('Ymd');

        // Get the last sale number for today
        $lastSale = static::where('sale_number', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->sale_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    /**
     * Get the customer associated with the sale
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the cashier that created the sale
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the sale items
     */
    public function saleItems()
    {
        return $this->hasMany(SaasInHouseSaleItem::class, 'sale_id');
    }

    /**
     * Calculate totals and update the sale
     */
    public function calculateTotals()
    {
        $subtotal = $this->saleItems->sum('total_price');

        $discountAmount = 0;
        if ($this->discount_amount > 0) {
            if ($this->discount_type === 'percentage') {
                $discountAmount = ($subtotal * $this->discount_amount) / 100;
            } else {
                $discountAmount = $this->discount_amount;
            }
        }

        $totalAfterDiscount = $subtotal - $discountAmount;
        $taxAmount = ($totalAfterDiscount * $this->tax_amount) / 100;
        $totalAmount = $totalAfterDiscount + $taxAmount + $this->shipping_amount;

        $this->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by payment status
     */
    public function scopePaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'success',
            'pending' => 'warning',
            'partial' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get total quantity of items in this sale
     */
    public function getTotalQuantityAttribute()
    {
        return $this->saleItems->sum('quantity');
    }

    /**
     * Get total revenue for dashboard metrics
     */
    public function scopeTotalRevenue($query)
    {
        return $query->sum('total_amount');
    }

    /**
     * Get today's sales count
     */
    public static function getTodaysSalesCount()
    {
        return static::whereDate('sale_date', today())->count();
    }

    /**
     * Get today's revenue
     */
    public static function getTodaysRevenue()
    {
        return static::whereDate('sale_date', today())->sum('total_amount');
    }

    /**
     * Get this month's revenue
     */
    public static function getThisMonthRevenue()
    {
        return static::whereMonth('sale_date', now()->month)
                    ->whereYear('sale_date', now()->year)
                    ->sum('total_amount');
    }
}
