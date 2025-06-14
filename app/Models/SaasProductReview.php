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
        'seller_response',
        'is_reported',
        'report_reason',
        'is_approved',
        'images',
    ];

    protected $casts = [
        'is_reported' => 'boolean',
        'is_approved' => 'boolean',
        'images' => 'array',
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

    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include reported reviews.
     */
    public function scopeReported($query)
    {
        return $query->where('is_reported', true);
    }

    /**
     * Check if the review has images.
     */
    public function hasImages()
    {
        return !empty($this->images);
    }

    /**
     * Get the review images URLs.
     */
    public function getImageUrls()
    {
        if (!$this->hasImages()) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }
}
