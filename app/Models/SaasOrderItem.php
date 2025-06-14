<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seller_id',
        'product_id',
        'variation_id',
        'quantity',
        'price',
        'discount',
        'tax',
        'status',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(SaasOrder::class);
    }

    /**
     * Get the seller of the item.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the product of the item.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class);
    }

    /**
     * Get the variation of the item.
     */
    public function productVariation()
    {
        return $this->belongsTo(SaasProductVariation::class, 'variation_id');
    }

    /**
     * Get the item total before discount and tax.
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get the item total after discount and tax.
     */
    public function getTotalAttribute()
    {
        return ($this->price * $this->quantity) - $this->discount + $this->tax;
    }
}
