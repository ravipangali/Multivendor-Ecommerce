<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'attribute_id',
        'attribute_value_id',
        'stock',
        'price',
        'discount',
        'discount_type',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class);
    }

    /**
     * Get the attribute for this variation.
     */
    public function attribute()
    {
        return $this->belongsTo(SaasAttribute::class);
    }

    /**
     * Get the attribute value for this variation.
     */
    public function attributeValue()
    {
        return $this->belongsTo(SaasAttributeValue::class);
    }

    /**
     * Get the order items for this variation.
     */
    public function orderItems()
    {
        return $this->hasMany(SaasOrderItem::class, 'variation_id');
    }

    /**
     * Get the final price after applying discount.
     */
    public function getFinalPriceAttribute()
    {
        $finalPrice = $this->price;

        if ($this->discount > 0) {
            if ($this->discount_type === 'percentage') {
                $finalPrice = $finalPrice - (($finalPrice * $this->discount) / 100);
            } else {
                $finalPrice = $finalPrice - $this->discount;
            }
        }

        return max(0, $finalPrice);
    }
}
