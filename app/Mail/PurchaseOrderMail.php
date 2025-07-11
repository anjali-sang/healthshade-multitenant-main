<?php

namespace App\Mail;

use App\Models\BillToLocation;
use App\Models\ShipToLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mergeId;
    public $supplier;
    public $purchaseOrderDetails;
    public $billToLocation;
    public $shipToLocation;

    public $organization;

    public $date;

    public function __construct($date,$organization, $mergeId, $supplier, $purchaseOrderDetails, $billToLocationId, $shipToLocationId)
    {
        $this->mergeId = $mergeId;
        $this->supplier = $supplier;
        $this->purchaseOrderDetails = $purchaseOrderDetails;
        $this->date = $date;
        $this->organization = $organization;

        // Fetch shared Bill To and Ship To info for display
        $this->billToLocation = BillToLocation::where('location_id', $billToLocationId)
            ->where('supplier_id', $supplier->id)
            ->with('location')
            ->first();

        $this->shipToLocation = ShipToLocation::where('location_id', $shipToLocationId)
            ->where('supplier_id', $supplier->id)
            ->with('location')
            ->first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Order Details - ' . $this->mergeId,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase_order',
            with: [
                'date' => $this->date,
                'organization' => $this->organization,
                'mergeId' => $this->mergeId,
                'purchaseOrderDetails' => $this->purchaseOrderDetails,
                'supplier' => $this->supplier,
                'bill_to' => $this->billToLocation,
                'ship_to' => $this->shipToLocation,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
