<?php

namespace App\Observers;

use App\Models\SaasOrder;
use App\Models\SaasSetting;
use App\Models\SaasTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SaasOrderObserver
{
    /**
     * Handle the SaasOrder "created" event.
     */
    public function created(SaasOrder $saasOrder): void
    {
        //
    }

    /**
     * Handle the SaasOrder "updated" event.
     */
    public function updated(SaasOrder $saasOrder): void
    {
        //
    }

    /**
     * Handle the SaasOrder "deleted" event.
     */
    public function deleted(SaasOrder $saasOrder): void
    {
        //
    }

    /**
     * Handle the SaasOrder "restored" event.
     */
    public function restored(SaasOrder $saasOrder): void
    {
        //
    }

    /**
     * Handle the SaasOrder "force deleted" event.
     */
    public function forceDeleted(SaasOrder $saasOrder): void
    {
        //
    }

    /**
     * Handle the SaasOrder "updating" event.
     */
    public function updating(SaasOrder $order)
    {
        // Get original values before update
        $original = $order->getOriginal();

        // Check if payment status is changing
        if (isset($original['payment_status']) && $original['payment_status'] !== $order->payment_status) {
            $this->handlePaymentStatusChange($order, $original['payment_status'], $order->payment_status);
        }
    }

    /**
     * Handle payment status changes
     */
    private function handlePaymentStatusChange(SaasOrder $order, $oldStatus, $newStatus)
    {
        try {
            Log::info('Payment status change detected', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'total' => $order->total
            ]);

            // Handle Pending to Paid
            if ($oldStatus === 'pending' && $newStatus === 'paid') {
                $this->handleOrderPaid($order);
            }

        } catch (\Exception $e) {
            Log::error('Error handling payment status change', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle when order is paid
     */
    private function handleOrderPaid(SaasOrder $order)
    {
        $settings = SaasSetting::first();
        if (!$settings) {
            Log::warning('No settings found for balance update');
            return;
        }

        // Update admin balance (add full order total)
        $settings->updateBalance(
            $order->total,
            SaasTransaction::TYPE_DEPOSIT,
            "Order payment received - Order #{$order->order_number}",
            $order->id
        );

        // Handle seller commission if order has a seller
        if ($order->seller_id) {
            $seller = User::find($order->seller_id);
            if (!$seller) {
                Log::warning('Seller not found for commission calculation', ['seller_id' => $order->seller_id]);
                return;
            }

            // Get effective commission rate
            $commissionRate = $seller->getEffectiveCommissionRate();

            // Calculate commission amount and seller earnings
            $commissionAmount = ($order->total * $commissionRate) / 100;
            $sellerAmount = $order->total - $commissionAmount;

            $transactionType = SaasTransaction::TYPE_COMMISSION;
            $description = "Commission earned from Order #{$order->order_number}";

            // Update seller balance
            $seller->updateBalance(
                $sellerAmount,
                $transactionType,
                $description,
                $order->id,
                $commissionRate,
                $commissionAmount
            );
        }

        Log::info('Order paid - balances updated', [
            'order_id' => $order->id,
            'admin_balance_updated' => $order->total,
            'seller_id' => $order->seller_id
        ]);
    }
}
