<?php

namespace App\Mail;

use App\Models\SaasOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaasOrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $previousStatus;
    public $recipient; // 'customer' or 'seller'

    /**
     * Create a new message instance.
     */
    public function __construct(SaasOrder $order, $previousStatus, $recipient = 'customer')
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->recipient = $recipient;
    }

        /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Order Status Update - #' . $this->order->order_number;

        // Choose email template based on recipient
        $template = $this->recipient === 'seller'
            ? 'emails.saas_seller.saas_order_status_changed'
            : 'emails.saas_customer.saas_order_status_changed';

        return $this->subject($subject)
                   ->view($template)
                   ->with([
                       'order' => $this->order,
                       'previousStatus' => $this->previousStatus,
                       'recipient' => $this->recipient,
                   ]);
    }
}
