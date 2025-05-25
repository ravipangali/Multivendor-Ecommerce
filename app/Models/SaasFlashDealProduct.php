<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasFlashDealProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_deal_id',
        'product_id',
        'discount_type',
        'discount_value',
    ];

    /**
     * Get the flash deal that owns the product.
     */
    public function flashDeal()
    {
        return $this->belongsTo(SaasFlashDeal::class);
    }

    /**
     * Get the product for this flash deal entry.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class);
    }

    /**
     * Calculate the discounted price for the product.
     */
    public function getDiscountedPriceAttribute()
    {
        $product = $this->product;

        if (!$product) {
            return 0;
        }

        $price = $product->price;

        if ($this->discount_type === 'percentage') {
            return $price - (($price * $this->discount_value) / 100);
        }

        return $price - $this->discount_value;
    }
}
