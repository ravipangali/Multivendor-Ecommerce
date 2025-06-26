<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SaasTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'order_id',
        'reference_type',
        'reference_id',
        'description',
        'commission_percentage',
        'commission_amount',
        'status',
        'meta_data',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'meta_data' => 'array',
        'transaction_date' => 'datetime',
    ];

    // Constants for transaction types
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_COMMISSION = 'commission';
    const TYPE_REFUND = 'refund';

    // Constants for reference types
    const REFERENCE_ORDER = 'order';
    const REFERENCE_WITHDRAWAL = 'withdrawal';
    const REFERENCE_MANUAL = 'manual';
    const REFERENCE_COMMISSION = 'commission';

    // Constants for status
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order related to this transaction
     */
    public function order()
    {
        return $this->belongsTo(SaasOrder::class, 'order_id');
    }

    /**
     * Get the related model based on reference_type and reference_id
     */
    public function reference()
    {
        switch ($this->reference_type) {
            case self::REFERENCE_ORDER:
                return $this->belongsTo(SaasOrder::class, 'reference_id');
            case self::REFERENCE_WITHDRAWAL:
                return $this->belongsTo(SaasWithdrawal::class, 'reference_id');
            default:
                return null;
        }
    }

    /**
     * Scope for specific transaction types
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Scope for seller transactions only
     */
    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('user_id', $sellerId);
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute()
    {
        $sign = in_array($this->transaction_type, [self::TYPE_DEPOSIT, self::TYPE_COMMISSION]) ? '+' : '-';
        return $sign . number_format($this->amount, 2);
    }

    /**
     * Get transaction type badge class
     */
    public function getTypeBadgeClassAttribute()
    {
        switch ($this->transaction_type) {
            case self::TYPE_DEPOSIT:
                return 'badge-success';
            case self::TYPE_COMMISSION:
                return 'badge-info';
            case self::TYPE_WITHDRAWAL:
                return 'badge-warning';
            case self::TYPE_REFUND:
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case self::STATUS_COMPLETED:
                return 'badge-success';
            case self::STATUS_PENDING:
                return 'badge-warning';
            case self::STATUS_FAILED:
                return 'badge-danger';
            case self::STATUS_CANCELLED:
                return 'badge-secondary';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Create a new transaction record
     */
    public static function createTransaction($data)
    {
        return self::create([
            'user_id' => $data['user_id'] ?? null,
            'transaction_type' => $data['transaction_type'],
            'amount' => abs($data['amount']),
            'balance_before' => $data['balance_before'] ?? 0,
            'balance_after' => $data['balance_after'] ?? 0,
            'order_id' => $data['order_id'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'description' => $data['description'] ?? null,
            'commission_percentage' => $data['commission_percentage'] ?? null,
            'commission_amount' => $data['commission_amount'] ?? null,
            'status' => $data['status'] ?? self::STATUS_COMPLETED,
            'meta_data' => $data['meta_data'] ?? null,
            'transaction_date' => $data['transaction_date'] ?? now(),
        ]);
    }
}
