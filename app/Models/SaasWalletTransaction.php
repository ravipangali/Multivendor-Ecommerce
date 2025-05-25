<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'fee',
        'balance_after',
        'currency',
        'status',
        'transactionable_type',
        'transactionable_id',
        'source',
        'reference_id',
        'description',
        'admin_id',
        'meta_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta_data' => 'json',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet()
    {
        return $this->belongsTo(SaasWallet::class);
    }

    /**
     * Get the admin who processed the transaction (if any).
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the parent model (order, withdrawal, etc.).
     */
    public function transactionable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include credit transactions.
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope a query to only include debit transactions.
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include transactions from a specific source.
     */
    public function scopeFromSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Get the transaction sign (+ for credit, - for debit).
     */
    public function getSignAttribute()
    {
        return $this->type === 'credit' ? '+' : '-';
    }

    /**
     * Get the formatted amount with sign.
     */
    public function getSignedAmountAttribute()
    {
        return $this->sign . number_format($this->amount, 2);
    }
}
