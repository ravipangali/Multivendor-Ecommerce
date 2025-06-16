<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaasInHouseSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'variation_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'discount_amount',
        'discount_type',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the sale that owns the sale item
     */
    public function sale()
    {
        return $this->belongsTo(SaasInHouseSale::class, 'sale_id');
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class, 'product_id');
    }

    /**
     * Get the product variation if any
     */
    public function variation()
    {
        return $this->belongsTo(SaasProductVariation::class, 'variation_id');
    }

    /**
     * Calculate the total price based on unit price, quantity and discount
     */
    public function calculateTotalPrice()
    {
        $subtotal = $this->unit_price * $this->quantity;

        $discountAmount = 0;
        if ($this->discount_amount > 0) {
            if ($this->discount_type === 'percentage') {
                $discountAmount = ($subtotal * $this->discount_amount) / 100;
            } else {
                $discountAmount = $this->discount_amount;
            }
        }

        $this->total_price = $subtotal - $discountAmount;
        return $this->total_price;
    }

    /**
     * Get the final price after discount
     */
    public function getFinalPriceAttribute()
    {
        $subtotal = $this->unit_price * $this->quantity;

        if ($this->discount_amount > 0) {
            if ($this->discount_type === 'percentage') {
                $discountAmount = ($subtotal * $this->discount_amount) / 100;
            } else {
                $discountAmount = $this->discount_amount;
            }
            return $subtotal - $discountAmount;
        }

        return $subtotal;
    }
}
