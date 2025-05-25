<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasWishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    /**
     * Get the customer that owns the wishlist item.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the product that is in the wishlist.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class);
    }
}
