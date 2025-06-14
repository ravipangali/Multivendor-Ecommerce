<?php

namespace App\Mail;

use App\Models\SaasOrder;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaasOrderConfirmation extends Mailable
{
    use SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(SaasOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Order Confirmation - Order #' . $this->order->order_number)
                    ->view('emails.saas_customer.saas_order_confirmation')
                    ->with([
                        'order' => $this->order,
                        'customer' => $this->order->customer,
                        'items' => $this->order->items->load(['product', 'productVariation']),
                    ]);
    }
}
