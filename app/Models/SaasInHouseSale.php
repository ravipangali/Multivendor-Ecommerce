<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaasInHouseSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'discount_type',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'paid_amount',
        'due_amount',
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
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
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
            'due_amount' => $totalAmount - $this->paid_amount,
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
}
