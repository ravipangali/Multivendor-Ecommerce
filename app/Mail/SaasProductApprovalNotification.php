<?php

namespace App\Mail;

use App\Models\SaasProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaasProductApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $status; // 'approved' or 'denied'

    /**
     * Create a new message instance.
     */
    public function __construct(SaasProduct $product, string $status)
    {
        $this->product = $product->load(['seller', 'category', 'brand']);
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? 'Product Approved: ' . $this->product->name
            : 'Product Denied: ' . $this->product->name;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.saas_seller.saas_product_approval',
            with: [
                'product' => $this->product,
                'seller' => $this->product->seller,
                'status' => $this->status,
                'isApproved' => $this->status === 'approved',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
