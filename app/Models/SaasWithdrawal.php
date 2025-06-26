<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SaasWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'type',
        'amount',
        'requested_amount',
        'fee',
        'gateway_fee',
        'final_amount',
        'net_amount',
        'currency',
        'status',
        'notes',
        'admin_notes',
        'admin_attachment',
        'reference_id',
        'processed_at',
        'rejected_reason',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'final_amount' => 'decimal:2',
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
     * Get the payment method used for this withdrawal
     */
    public function paymentMethod()
    {
        return $this->belongsTo(SaasPaymentMethod::class);
    }

    /**
     * Get the admin who processed the withdrawal.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Request a new seller withdrawal.
     */
    public static function requestSellerWithdrawal($sellerId, $requestedAmount, $paymentMethodId, $notes = null)
    {
        $seller = User::findOrFail($sellerId);

        // Verify seller role
        if (!$seller->isSeller()) {
            throw new \InvalidArgumentException('Only sellers can request withdrawals');
        }

        $paymentMethod = SaasPaymentMethod::where('id', $paymentMethodId)
            ->where('user_id', $sellerId)
            ->where('is_active', true)
            ->firstOrFail();

        if ($seller->balance < $requestedAmount) {
            throw new \InvalidArgumentException('Insufficient balance for withdrawal');
        }

        // Get gateway transaction fee from settings
        $settings = SaasSetting::first();
        $gatewayFee = $settings ? $settings->gateway_transaction_fee : 0;

        // Calculate final amount (requested amount - gateway fee)
        $finalAmount = $requestedAmount - $gatewayFee;

        if ($finalAmount <= 0) {
            throw new \InvalidArgumentException('Withdrawal amount too small after gateway fees');
        }

        return self::create([
            'user_id' => $sellerId,
            'payment_method_id' => $paymentMethod->id,
            'type' => 'seller_withdrawal',
            'requested_amount' => $requestedAmount,
            'amount' => $requestedAmount, // Keep for compatibility
            'gateway_fee' => $gatewayFee,
            'final_amount' => $finalAmount,
            'net_amount' => $finalAmount, // Keep for compatibility
            'currency' => 'NPR',
            'status' => 'pending',
            'notes' => $notes,
        ]);
    }

    /**
     * Approve the withdrawal request.
     */
    public function approveWithdrawal($adminId, $adminNotes = null, $attachmentFile = null)
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending withdrawals can be approved');
        }

        DB::beginTransaction();

        try {
            // Handle attachment upload
            $attachmentPath = null;
            if ($attachmentFile) {
                $attachmentPath = $attachmentFile->store('withdrawal_attachments', 'public');
            }

            // Update withdrawal status
            $this->update([
                'status' => 'approved',
                'admin_notes' => $adminNotes,
                'admin_attachment' => $attachmentPath,
                'processed_at' => now(),
                'processed_by' => $adminId,
            ]);

            // Deduct requested amount from seller balance
            $this->user->updateBalance(
                $this->requested_amount,
                SaasTransaction::TYPE_WITHDRAWAL,
                "Withdrawal approved - Request #{$this->id}",
                null
            );

            // Deduct final amount from admin balance
            $settings = SaasSetting::first();
            if ($settings) {
                $settings->updateBalance(
                    $this->final_amount,
                    SaasTransaction::TYPE_WITHDRAWAL,
                    "Seller withdrawal processed - Request #{$this->id}",
                    null
                );
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Reject a withdrawal request.
     */
    public function rejectWithdrawal($adminId, $reason, $adminNotes = null)
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending withdrawals can be rejected');
        }

        $this->update([
            'status' => 'rejected',
            'rejected_reason' => $reason,
            'admin_notes' => $adminNotes,
            'processed_at' => now(),
            'processed_by' => $adminId,
        ]);

        return true;
    }

    /**
     * Cancel a withdrawal request (by seller).
     */
    public function cancelWithdrawal()
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending withdrawals can be cancelled');
        }

        $this->update([
            'status' => 'cancelled',
            'processed_at' => now(),
        ]);

        return true;
    }

    /**
     * Get admin attachment URL.
     */
    public function getAdminAttachmentUrlAttribute()
    {
        return $this->admin_attachment ? Storage::url($this->admin_attachment) : null;
    }

    /**
     * Scope for pending withdrawals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved withdrawals.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for seller withdrawals.
     */
    public function scopeSellerWithdrawals($query)
    {
        return $query->where('type', 'seller_withdrawal');
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'processing' => 'info',
            'completed' => 'primary',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get the related transaction.
     */
    public function transaction()
    {
        return $this->hasOne(SaasTransaction::class, 'reference_id')
                    ->where('reference_type', SaasTransaction::REFERENCE_WITHDRAWAL);
    }
}
