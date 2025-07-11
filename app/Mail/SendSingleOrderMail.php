<?php

namespace App\Mail;

use App\Models\BillToLocation;
use App\Models\ShipToLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSingleOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $purchaseOrder;
    public $supplier;
    public $purchaseOrderDetails;

    public $organization;
    public function __construct($organization, $purchaseOrder, $supplier, $purchaseOrderDetails = null)
    {
        $this->organization = $organization;
        $this->purchaseOrder = $purchaseOrder;
        $this->supplier = $supplier;
        $this->purchaseOrderDetails = $purchaseOrderDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Order details- ' . $this->purchaseOrder->purchase_order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $shipToLocationSupplier = ShipToLocation::where('location_id', $this->purchaseOrder->ship_to_location_id)
            ->where('supplier_id', $this->supplier->id)
            ->first();
        $billToLocationSupplier = BillToLocation::where('location_id', $this->purchaseOrder->bill_to_location_id)
            ->where('supplier_id', $this->supplier->id)
            ->first();
        return new Content(
            view: 'emails.single_purchase_order',
            with: [
                'organization' => $this->organization,
                'purchaseOrder' => $this->purchaseOrder,
                'purchaseOrderDetails' => $this->purchaseOrderDetails,
                'supplier' => $this->supplier,
                'bill_to' => $billToLocationSupplier,
                'ship_to' => $shipToLocationSupplier
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
