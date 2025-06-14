<?php

namespace App\Mail;

use App\Models\SaasOrder;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaasOrderStatusUpdate extends Mailable
{
    use SerializesModels;

    public $order;
    public $previousStatus;
    public $recipientType; // 'customer' or 'vendor'

    /**
     * Create a new message instance.
     */
    public function __construct(SaasOrder $order, $previousStatus, $recipientType = 'customer')
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->recipientType = $recipientType;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Order Status Update - Order #' . $this->order->order_number;

        if ($this->recipientType === 'vendor') {
            $view = 'emails.saas_seller.saas_order_status_update';
        } else {
            $view = 'emails.saas_customer.saas_order_status_update';
        }

        return $this->subject($subject)
                    ->view($view)
                    ->with([
                        'order' => $this->order,
                        'previousStatus' => $this->previousStatus,
                        'currentStatus' => $this->order->order_status,
                        'customer' => $this->order->customer,
                        'seller' => $this->order->seller,
                        'items' => $this->order->items->load(['product', 'productVariation']),
                    ]);
    }
}
