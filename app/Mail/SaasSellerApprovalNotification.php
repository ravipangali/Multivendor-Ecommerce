<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaasSellerApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $status; // 'approved' or 'denied'

    /**
     * Create a new message instance.
     */
    public function __construct(User $seller, string $status)
    {
        $this->seller = $seller->load('sellerProfile');
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? 'Seller Account Approved - Welcome to Our Marketplace!'
            : 'Seller Account Status Update';

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
            view: 'emails.saas_seller.saas_seller_approval',
            with: [
                'seller' => $this->seller,
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
