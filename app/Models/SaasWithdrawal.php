<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaasWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'payment_method_id',
        'amount',
        'fee',
        'net_amount',
        'currency',
        'status',
        'notes',
        'admin_notes',
        'reference_id',
        'processed_at',
        'rejected_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'processed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the withdrawal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet this withdrawal is from.
     */
    public function wallet()
    {
        return $this->belongsTo(SaasWallet::class);
    }

    /**
     * Get the payment method used for this withdrawal
     */
    public function paymentMethod()
    {
        return $this->belongsTo(SaasPaymentMethod::class);
    }

    /**
     * Request a new withdrawal.
     *
     * @param int $userId User ID
     * @param float $amount Amount to withdraw
     * @param int $paymentMethodId Payment method ID to use
     * @param string $notes Notes from the user
     * @param float $fee Fee amount (if any)
     * @return \App\Models\SaasWithdrawal
     */
    public static function requestWithdrawal($userId, $amount, $paymentMethodId, $notes = null, $fee = 0)
    {
        $user = User::findOrFail($userId);
        $wallet = SaasWallet::where('user_id', $userId)->firstOrFail();
        $paymentMethod = SaasPaymentMethod::where('id', $paymentMethodId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->firstOrFail();

        if ($wallet->balance < $amount) {
            throw new \InvalidArgumentException('Insufficient balance for withdrawal');
        }

        $netAmount = $amount - $fee;

        DB::beginTransaction();

        try {
            // Create the withdrawal request
            $withdrawal = self::create([
                'user_id' => $userId,
                'wallet_id' => $wallet->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'currency' => $wallet->currency,
                'status' => 'pending',
                'notes' => $notes,
            ]);

            // Deduct the amount from wallet (pending withdrawal)
            $wallet->debit(
                $amount,
                'withdrawal',
                "Withdrawal request #{$withdrawal->id}",
                ['withdrawal_id' => $withdrawal->id],
                $fee
            );

            DB::commit();
            return $withdrawal;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Process a withdrawal request (approve/complete).
     *
     * @param string $referenceId External reference ID (transaction ID, etc.)
     * @param string $adminNotes Notes from admin
     * @param int $adminId ID of admin who processed the withdrawal
     * @return bool
     */
    public function processWithdrawal($referenceId, $adminNotes = null, $adminId = null)
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending withdrawals can be processed');
        }

        $this->status = 'completed';
        $this->reference_id = $referenceId;
        $this->admin_notes = $adminNotes;
        $this->processed_at = now();
        return $this->save();
    }

    /**
     * Reject a withdrawal request.
     *
     * @param string $reason Reason for rejection
     * @param string $adminNotes Additional notes from admin
     * @param int $adminId ID of admin who rejected the withdrawal
     * @return bool
     */
    public function rejectWithdrawal($reason, $adminNotes = null, $adminId = null)
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending withdrawals can be rejected');
        }

        DB::beginTransaction();

        try {
            // Update withdrawal status
            $this->status = 'rejected';
            $this->rejected_reason = $reason;
            $this->admin_notes = $adminNotes;
            $this->processed_at = now();
            $this->save();

            // Refund the amount to the wallet
            $wallet = $this->wallet;
            $wallet->credit(
                $this->amount,
                'withdrawal_refund',
                "Refund for rejected withdrawal #{$this->id}",
                ['withdrawal_id' => $this->id]
            );

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Cancel a withdrawal request (by user).
     *
     * @return bool
     */
    public function cancelWithdrawal()
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending withdrawals can be cancelled');
        }

        DB::beginTransaction();

        try {
            // Update withdrawal status
            $this->status = 'cancelled';
            $this->save();

            // Refund the amount to the wallet
            $wallet = $this->wallet;
            $wallet->credit(
                $this->amount,
                'withdrawal_cancelled',
                "Refund for cancelled withdrawal #{$this->id}",
                ['withdrawal_id' => $this->id]
            );

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get the transaction associated with this withdrawal.
     */
    public function transaction()
    {
        return SaasWalletTransaction::where('source', 'withdrawal')
            ->where('meta_data->withdrawal_id', $this->id)
            ->first();
    }
}
