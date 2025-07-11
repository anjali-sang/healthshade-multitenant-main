<?php

namespace App\Console\Commands;

use App\Mail\PurchaseOrderFailedMail;
use App\Models\Organization;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Services\StaplesPunchoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendStaplesOrder extends Command
{
    protected $signature = 'staples:place-order';
    protected $description = 'Send Staples purchase order grouped by shipping location';

    public function handle()
    {
        logger(now()->format('Y-m-d H:i:s') . ' - Sending Purchase Orders for Staples...');

        $purchaseOrders = PurchaseOrder::where('status', 'ordered')
            ->where('is_order_placed', false)
            ->whereHas('organization.subscription', fn($query) =>
                $query->where('name', '!=', 'free trial'))
            ->whereHas('purchaseSupplier', fn($query) =>
                $query->whereNotNull('supplier_email')
                    ->where('supplier_slug', 'staples'))
            ->with(['organization.subscription', 'purchaseSupplier', 'shippingLocation', 'billingLocation', 'createdUser'])
            ->get();

        if ($purchaseOrders->isEmpty()) {
            logger('No eligible Purchase Orders found.');
            return;
        }

        foreach ($purchaseOrders as $po) {
            try {
                $data = [];

                $data['payLoadID'] = 'StaplesPunchout_' . uniqid();
                $data['timestamp'] = now()->format('Y-m-d\TH:i:s');
                $data['customerIdentity'] = 'asthmalleh';
                $data['toIdentity'] = 'staples';
                $data['senderIdentity'] = 'asthmalleh';
                $data['sharedSecret'] = 'staples';
                $data['userAgent'] = 'Healthshade. - dispatcherPO.xml, v1.0';
                $data['total'] = $po->total;
                $data['orderId'] = $po->purchase_oder_number;
                $data['orderDate'] = $po->created_at->format('Y-m-d\TH:i:s');
                $data['shiptoAddressId'] = $po->ship_to_number;
                $data['shiptoName'] = $po->shippingLocation->name;
                $data['deliverTo'] = $po->shippingLocation->address;
                $data['shiptoCity'] = $po->shippingLocation->city;
                $data['shiptoState'] = $po->shippingLocation->state;
                $data['shiptoPostalCode'] = $po->shippingLocation->pin;
                $data['shiptoCountry'] = $po->shippingLocation->country;
                $data['shiptoEmail'] = $po->shippingLocation->email;
                $data['shiptoPhoneNumber'] = $po->shippingLocation->phone;
                $data['billToAddressId'] = $po->bill_to_number;
                $data['billToAddress'] = $po->billingLocation->address;
                $data['billToCity'] = $po->billingLocation->city;
                $data['billToState'] = $po->billingLocation->state;
                $data['billToPostalCode'] = $po->billingLocation->pin;
                $data['billToCountry'] = $po->billingLocation->country;
                $data['billToEmail'] = $po->billingLocation->email;
                $data['billToPhoneNumber'] = $po->billingLocation->phone;
                $data['contactName'] = $po->createdUser->name ?? 'NA';
                $data['contactEmail'] = $po->createdUser->email ?? 'NA';
                $data['billToName'] = $po->billingLocation->name ?? '';
                $data['production'] = config('services.staples.production') ? 'production' : 'test';

                $data['items'] = [];
                $details = PurchaseOrderDetail::where('purchase_order_id', $po->id)->with(['product', 'unit'])->get();

                $index = 0;
                foreach ($details as $detail) {
                    $index++;
                    $data['items'][] = [
                        'quantity' => $detail->quantity,
                        'lineNumber' => $index,
                        'code' => $detail->product->product_code,
                        'price' => $detail->sub_total / max($detail->quantity, 1),
                        'description' => $detail->product->name,
                        'uom' => $detail->unit->unit_name,
                    ];
                }

                $staplesService = new StaplesPunchoutService();
                $response = $staplesService->send($data);

                if (!$response->successful()) {
                    logger("Failed to send Staples PO for Order ID: {$po->purchase_oder_number}");
                    $this->handleOrderFailure(collect([$po]), 'No XML response returned from Staples');
                    continue;
                }

                $po->is_order_placed = true;
                $po->save();

                logger("Staples PO sent successfully for Order ID: {$po->purchase_oder_number}");
            } catch (\Exception $e) {
                logger()->error("Exception while processing PO {$po->purchase_oder_number}: " . $e->getMessage());
                $this->handleOrderFailure(collect([$po]), $e->getMessage());
            }
        }
    }

    private function handleOrderFailure($failedOrders, $errorMessage)
    {
        try {
            if ($failedOrders->isEmpty()) {
                logger('No failed orders to handle.');
                return;
            }

            $organization = Organization::find($failedOrders->first()->organization_id);
            if (!$organization) {
                logger('Could not find organization for failed orders');
            }

            $notificationEmails = (array) config('app.email');

            Mail::to($notificationEmails)->send(
                new PurchaseOrderFailedMail(
                    $organization,
                    $failedOrders,
                    $errorMessage
                )
            );

            foreach ($failedOrders as $order) {
                $order->note = "Failed to place order: " . $errorMessage;
                $order->save();
            }

            logger("Sent failure notification for {$failedOrders->count()} order(s) to: " . implode(', ', $notificationEmails));
        } catch (\Exception $e) {
            logger("Failed to send failure notification email: " . $e->getMessage());
        }
    }
}
