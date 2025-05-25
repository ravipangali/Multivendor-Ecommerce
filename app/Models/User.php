<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'profile_photo',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is seller
     */
    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the seller profile associated with the user.
     */
    public function sellerProfile()
    {
        return $this->hasOne(SaasSellerProfile::class);
    }

    /**
     * Get the customer profile associated with the user.
     */
    public function customerProfile()
    {
        return $this->hasOne(SaasCustomerProfile::class);
    }

    /**
     * Get the products for the seller.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'seller_id');
    }

    /**
     * Get the orders placed by the customer.
     */
    public function customerOrders()
    {
        return $this->hasMany(SaasOrder::class, 'customer_id');
    }

    /**
     * Get the orders received by the seller.
     */
    public function sellerOrders()
    {
        return $this->hasMany(SaasOrder::class, 'seller_id');
    }

    /**
     * Get the wishlists for the customer.
     */
    public function wishlists()
    {
        return $this->hasMany(SaasWishlist::class, 'customer_id');
    }

    /**
     * Get the reviews written by the customer.
     */
    public function reviews()
    {
        return $this->hasMany(SaasProductReview::class, 'customer_id');
    }

    /**
     * Get the coupons created by the seller.
     */
    public function coupons()
    {
        return $this->hasMany(SaasCoupon::class, 'seller_id');
    }

    /**
     * Get the wallet belonging to the user.
     */
    public function wallet()
    {
        return $this->hasOne(SaasWallet::class);
    }

    /**
     * Get all wallets for the user (in different currencies).
     */
    public function wallets()
    {
        return $this->hasMany(SaasWallet::class);
    }

    /**
     * Get the withdrawals made by the user.
     */
    public function withdrawals()
    {
        return $this->hasMany(SaasWithdrawal::class);
    }

    /**
     * Get the wallet transactions for the user.
     */
    public function walletTransactions()
    {
        return $this->hasMany(SaasWalletTransaction::class);
    }

    /**
     * Get the user's primary wallet, creating it if it doesn't exist.
     */
    public function getWallet($currency = 'NPR')
    {
        return SaasWallet::getOrCreate($this->id, $currency);
    }
}
