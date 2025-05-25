<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasCustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address',
        'billing_address',
    ];

    /**
     * Get the user that owns the customer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wishlists for the customer.
     */
    public function wishlists()
    {
        return $this->hasMany(SaasWishlist::class, 'customer_id', 'user_id');
    }

    /**
     * Get the orders for the customer.
     */
    public function orders()
    {
        return $this->hasMany(SaasOrder::class, 'customer_id', 'user_id');
    }

    /**
     * Get the product reviews for the customer.
     */
    public function reviews()
    {
        return $this->hasMany(SaasProductReview::class, 'customer_id', 'user_id');
    }
}
