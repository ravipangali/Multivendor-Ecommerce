<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'seller_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the seller that owns the coupon.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid()
    {
        $now = now();

        return $this->start_date <= $now &&
               $this->end_date >= $now &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    /**
     * Calculate the discount for a given subtotal.
     */
    public function calculateDiscount($subtotal)
    {
        if ($this->discount_type === 'percentage') {
            return ($subtotal * $this->discount_value) / 100;
        }

        return min($this->discount_value, $subtotal);
    }

    /**
     * Increment the used count.
     */
    public function incrementUsedCount()
    {
        $this->increment('used_count');
    }
}
