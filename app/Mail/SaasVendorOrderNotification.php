<?php

namespace App\Mail;

use App\Models\SaasOrder;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaasVendorOrderNotification extends Mailable
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
        return $this->subject('New Order Received - Order #' . $this->order->order_number)
                    ->view('emails.saas_seller.saas_order_notification')
                    ->with([
                        'order' => $this->order,
                        'seller' => $this->order->seller,
                        'customer' => $this->order->customer,
                        'items' => $this->order->items->load(['product', 'productVariation']),
                    ]);
    }
}
