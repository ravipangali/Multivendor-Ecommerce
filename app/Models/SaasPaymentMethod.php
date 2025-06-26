<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'details',
        'is_default',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'details' => 'array',
    ];

    /**
     * Get the user that owns the payment method.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the withdrawals made using this payment method.
     */
    public function withdrawals()
    {
        return $this->hasMany(SaasWithdrawal::class, 'payment_method_id');
    }

    /**
     * Set this payment method as the default for the user.
     */
    public function setAsDefault()
    {
        // Begin by unsetting all other default payment methods for this user
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set this payment method as default
        $this->is_default = true;
        return $this->save();
    }

    /**
     * Scope a query to only include active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include methods of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get a formatted display name for the payment method.
     */
    public function getDisplayNameAttribute()
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        switch ($this->type) {
            case 'bank_transfer':
                return 'Bank Transfer';
            case 'esewa':
                return 'eSewa';
            case 'khalti':
                return 'Khalti';
            default:
                return ucfirst(str_replace('_', ' ', $this->type));
        }
    }
}
