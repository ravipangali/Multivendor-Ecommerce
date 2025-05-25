<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaasWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'total_withdrawn',
        'total_earned',
        'currency',
        'last_activity_at',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'is_active' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions()
    {
        return $this->hasMany(SaasWalletTransaction::class, 'wallet_id');
    }

    /**
     * Get the withdrawals for the wallet.
     */
    public function withdrawals()
    {
        return $this->hasMany(SaasWithdrawal::class, 'wallet_id');
    }

    /**
     * Get the payment methods for the wallet's user.
     */
    public function paymentMethods()
    {
        return $this->hasMany(SaasPaymentMethod::class, 'user_id', 'user_id');
    }

    /**
     * Credit the wallet with the specified amount.
     */
    public function credit($amount, $source = 'order', $description = null, $meta = [], $isPending = false)
    {
        // Validate amount
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive');
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Update wallet balance
            if ($isPending) {
                $this->pending_balance += $amount;
            } else {
                $this->balance += $amount;
            }

            $this->total_earned += $amount;
            $this->last_activity_at = now();
            $this->save();

            // Create transaction record
            $transaction = new SaasWalletTransaction([
                'user_id' => $this->user_id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $this->balance,
                'currency' => $this->currency,
                'source' => $source,
                'description' => $description,
                'status' => $isPending ? 'pending' : 'completed',
                'meta_data' => $meta,
            ]);

            $this->transactions()->save($transaction);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Debit the wallet with the specified amount.
     */
    public function debit($amount, $source = 'withdrawal', $description = null, $meta = [], $fee = 0)
    {
        // Validate amount
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Debit amount must be positive');
        }

        // Check balance
        if ($this->balance < $amount) {
            throw new \InvalidArgumentException('Insufficient balance for debit operation');
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Update wallet balance
            $this->balance -= $amount;

            if ($source === 'withdrawal') {
                $this->total_withdrawn += $amount - $fee; // Only count the actual withdrawn amount, not fees
            }

            $this->last_activity_at = now();
            $this->save();

            // Create transaction record
            $transaction = new SaasWalletTransaction([
                'user_id' => $this->user_id,
                'type' => 'debit',
                'amount' => $amount,
                'fee' => $fee,
                'balance_after' => $this->balance,
                'currency' => $this->currency,
                'source' => $source,
                'description' => $description,
                'status' => 'completed',
                'meta_data' => $meta,
            ]);

            $this->transactions()->save($transaction);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Move funds from pending to available balance.
     */
    public function releasePendingFunds($amount, $description = null, $meta = [])
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        if ($this->pending_balance < $amount) {
            throw new \InvalidArgumentException('Insufficient pending balance');
        }

        DB::beginTransaction();

        try {
            // Move from pending to available
            $this->pending_balance -= $amount;
            $this->balance += $amount;
            $this->last_activity_at = now();
            $this->save();

            // Log the transaction
            $transaction = new SaasWalletTransaction([
                'user_id' => $this->user_id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $this->balance,
                'currency' => $this->currency,
                'source' => 'pending_release',
                'description' => $description ?: 'Funds released from pending balance',
                'status' => 'completed',
                'meta_data' => $meta,
            ]);

            $this->transactions()->save($transaction);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get wallet for a user, creating it if it doesn't exist.
     */
    public static function getOrCreate($userId, $currency = 'NPR')
    {
        $wallet = self::firstOrCreate(
            ['user_id' => $userId, 'currency' => $currency],
            [
                'balance' => 0,
                'pending_balance' => 0,
                'last_activity_at' => now(),
                'is_active' => true,
            ]
        );

        return $wallet;
    }

    /**
     * Get the available balance for withdrawal.
     */
    public function getAvailableForWithdrawalAttribute()
    {
        return $this->balance;
    }
}
