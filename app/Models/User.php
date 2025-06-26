<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SaasResetPasswordNotification;
use Illuminate\Support\Facades\DB;

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
        'commission',
        'balance',
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
        'commission' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SaasResetPasswordNotification($token));
    }

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
        return $this->hasOne(SaasCustomerProfile::class, 'user_id');
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
     * Get the reviews for products sold by this seller.
     */
    public function productReviews()
    {
        return $this->hasMany(SaasProductReview::class, 'seller_id');
    }

    /**
     * Get the coupons created by the seller.
     */
    public function coupons()
    {
        return $this->hasMany(SaasCoupon::class, 'seller_id');
    }

    /**
     * Get the withdrawals made by the user.
     */
    public function withdrawals()
    {
        return $this->hasMany(SaasWithdrawal::class);
    }

    /**
     * Get transactions for this user
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\SaasTransaction::class);
    }

    /**
     * Update user balance and create transaction record
     */
    public function updateBalance($amount, $transactionType, $description = null, $orderId = null, $commissionPercentage = null, $commissionAmount = null)
    {
        $balanceBefore = $this->balance;

        if (in_array($transactionType, [\App\Models\SaasTransaction::TYPE_DEPOSIT, \App\Models\SaasTransaction::TYPE_COMMISSION])) {
            $this->balance += $amount;
        } else {
            $this->balance -= $amount;
        }

        $this->save();

        // Create transaction record
        return \App\Models\SaasTransaction::createTransaction([
            'user_id' => $this->id,
            'transaction_type' => $transactionType,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'order_id' => $orderId,
            'reference_type' => $orderId ? \App\Models\SaasTransaction::REFERENCE_ORDER : null,
            'reference_id' => $orderId,
            'description' => $description,
            'commission_percentage' => $commissionPercentage,
            'commission_amount' => $commissionAmount,
        ]);
    }

    /**
     * Get effective commission rate for this seller
     */
    public function getEffectiveCommissionRate()
    {
        if ($this->commission && $this->commission > 0) {
            return $this->commission;
        }

        $settings = \App\Models\SaasSetting::first();
        return $settings ? $settings->seller_commission : 0;
    }
}
