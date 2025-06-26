<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SaasRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'seller_id',
        'payment_method_id',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'refund_amount',
        'seller_deduct_amount',
        'currency',
        'status',
        'customer_reason',
        'admin_notes',
        'admin_attachment',
        'processed_at',
        'rejected_reason',
        'processed_by',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'seller_deduct_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the customer who requested the refund.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the order being refunded.
     */
    public function order()
    {
        return $this->belongsTo(SaasOrder::class, 'order_id');
    }

    /**
     * Get the seller affected by the refund.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the payment method for refund.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(SaasPaymentMethod::class, 'payment_method_id');
    }

    /**
     * Get the admin who processed the refund.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Create a new refund request.
     */
    public static function createRefundRequest($customerId, $orderId, $paymentMethodId, $reason)
    {
        $order = SaasOrder::findOrFail($orderId);

        // Verify the order belongs to the customer
        if ($order->customer_id !== $customerId) {
            throw new \InvalidArgumentException('Order does not belong to the customer');
        }

        // Check if order is eligible for refund
        if (!in_array($order->order_status, ['delivered', 'completed'])) {
            throw new \InvalidArgumentException('Order is not eligible for refund');
        }

        // Check if refund already exists
        if (self::where('order_id', $orderId)->whereIn('status', ['pending', 'approved', 'processed'])->exists()) {
            throw new \InvalidArgumentException('Refund request already exists for this order');
        }

        // Calculate commission details
        $seller = $order->seller;
        $commissionRate = $seller ? $seller->getEffectiveCommissionRate() : 0;
        $commissionAmount = ($order->total * $commissionRate) / 100;
        $sellerDeductAmount = $order->total - $commissionAmount;

        return self::create([
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'seller_id' => $order->seller_id,
            'payment_method_id' => $paymentMethodId,
            'order_amount' => $order->total,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'refund_amount' => $order->total,
            'seller_deduct_amount' => $sellerDeductAmount,
            'currency' => 'NPR',
            'status' => 'pending',
            'customer_reason' => $reason,
        ]);
    }

    /**
     * Approve and process the refund.
     */
    public function approveRefund($adminId, $adminNotes = null, $attachmentFile = null)
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending refunds can be approved');
        }

        DB::beginTransaction();

        try {
            // Handle attachment upload
            $attachmentPath = null;
            if ($attachmentFile) {
                $attachmentPath = $attachmentFile->store('refund_attachments', 'public');
            }

            // Update refund status
            $this->update([
                'status' => 'approved',
                'admin_notes' => $adminNotes,
                'admin_attachment' => $attachmentPath,
                'processed_at' => now(),
                'processed_by' => $adminId,
            ]);

            // Update admin balance (subtract full refund amount)
            $settings = SaasSetting::first();
            if ($settings) {
                $settings->updateBalance(
                    $this->refund_amount,
                    SaasTransaction::TYPE_REFUND,
                    "Customer refund processed - Order #{$this->order->order_number}",
                    $this->order_id
                );
            }

            // Update seller balance (subtract seller deduct amount)
            if ($this->seller) {
                $this->seller->updateBalance(
                    $this->seller_deduct_amount,
                    SaasTransaction::TYPE_REFUND,
                    "Refund deduction - Order #{$this->order->order_number}",
                    $this->order_id,
                    $this->commission_rate,
                    $this->commission_amount
                );
            }

            // Update order status to refunded
            $this->order->update([
                'order_status' => 'refunded',
                'payment_status' => 'refunded'
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Reject the refund request.
     */
    public function rejectRefund($adminId, $reason, $adminNotes = null)
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending refunds can be rejected');
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
     * Get admin attachment URL.
     */
    public function getAdminAttachmentUrlAttribute()
    {
        return $this->admin_attachment ? Storage::url($this->admin_attachment) : null;
    }

    /**
     * Scope for pending refunds.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved refunds.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'processed' => 'info',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}
