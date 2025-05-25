<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'seller_id',
        'rating',
        'review',
    ];

    /**
     * Get the customer that wrote the review.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the product that was reviewed.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class);
    }

    /**
     * Get the seller of the product.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
