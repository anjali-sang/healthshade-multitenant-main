<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PurchaseOrderFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $organization;
    public $failedOrders;
    public $errorMessage;
    public $failureTimestamp;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $organization,
        Collection $failedOrders,
        string $errorMessage
    ) {
        $this->organization = $organization;
        $this->failedOrders = $failedOrders;
        $this->errorMessage = $errorMessage;
        $this->failureTimestamp = now();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Order Placement Failed ' . $this->organization?->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase-order-failed',
            with: [
                'organization' => $this->organization,
                'failedOrders' => $this->failedOrders,
                'errorMessage' => $this->errorMessage,
                'failureTimestamp' => $this->failureTimestamp,
                'totalFailedOrders' => $this->failedOrders->count(),
            ]
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