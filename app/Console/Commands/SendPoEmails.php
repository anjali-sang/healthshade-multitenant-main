<?php

namespace App\Console\Commands;

use App\Mail\PurchaseOrderFailedMail;
use App\Mail\PurchaseOrderMail;
use App\Mail\SendSingleOrderMail;
use App\Models\Organization;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPoEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'po:send-emails';
    protected $description = 'Send emails related to pending Purchase Orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            logger(now()->format('Y-m-d H:i:s') . ' - Fetching Purchase Orders for eligible organizations...');
            $purchaseOrders = PurchaseOrder::where('status', 'ordered')
                ->where('is_order_placed', false)
                ->whereHas('organization.subscription', function ($query) {
                    $query->where('name', '!=', 'free trial');
                })
                ->whereHas('purchaseSupplier', function ($query) {
                    $query->whereNotNull('supplier_email')
                        ->where('supplier_email', '!=', '')
                        ->whereNotIn('supplier_slug', ['henryschien', 'staples']);
                })
                ->with(['organization.subscription', 'purchaseSupplier', 'shippingLocation'])
                ->get();
            if ($purchaseOrders->isEmpty()) {
                logger('No eligible Purchase Orders found.');
                return;
            }
            // Group by ship_to_location_id and supplier_id
            $groupedPOs = $purchaseOrders->groupBy(function ($po) {
                return $po->ship_to_location_id . '|' . $po->supplier_id;
            });

            foreach ($groupedPOs as $key => $poGroup) {
                $organization = Organization::where('id', $poGroup->first()->organization_id)->first();
                $supplier = $poGroup->first()->purchaseSupplier;
                $date = $poGroup->first()->created_at;
                $supplierName = $supplier->supplier_name ?? 'Unknown Supplier';
                $shippingLocationName = $poGroup->first()->shippingLocation->name ?? 'Unknown Location';
                $billToLocationId = $poGroup->first()->bill_to_location_id;
                $shipToLocationId = $poGroup->first()->ship_to_location_id;

                logger("Processing group: Supplier: {$supplierName}, Shipping Location: {$shippingLocationName}, Orders Count: " . $poGroup->count());
                if ($poGroup->count() == 1) {
                    $purchaseOrder = $poGroup->first();
                    $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)
                        ->with('product')
                        ->get();
                    $users = User::where('location_id', $purchaseOrder->location_id)
                        ->where('is_active', true)
                        ->get();
                    $ccUsers = [
                        $purchaseOrder->createdUser->email ?? null,
                        config('app.email'),
                        ...$users->pluck('email')->toArray(),
                    ];
                    $ccUsers = array_filter($ccUsers);
                    Mail::to($supplier->supplier_email)
                        ->cc($ccUsers)
                        ->send(new SendSingleOrderMail(
                            $organization,
                            $purchaseOrder,
                            $supplier,
                            $purchaseOrderDetails
                        ));

                    $purchaseOrder->is_order_placed = true;
                    $purchaseOrder->note = "Order is placed to supplier directly.";
                    $purchaseOrder->save();
                } else {
                    // Generate unique merge ID for this group
                    $mergeId = PurchaseOrder::generateMergeId();
                    // Gather all purchase order details from the group
                    $allPurchaseOrderDetails = collect();

                    foreach ($poGroup as $purchaseOrder) {
                        $details = PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)
                            ->with('product')
                            ->get();
                        $allPurchaseOrderDetails = $allPurchaseOrderDetails->merge($details);
                    }

                    $firstOrder = $poGroup->first();
                    $organizationId = $firstOrder->organization_id ?? null;
                    $users = User::where('location_id', $firstOrder->location_id)
                        ->where('is_active', true)
                        ->get();
                    $ccUsers = [
                        $firstOrder->createdUser->email ?? null,
                        config('app.email'),
                        ...$users->pluck('email')->toArray(),
                    ];
                    $ccUsers = array_filter($ccUsers);
                    // Send mail with merge ID instead of individual PO number
                    Mail::to($supplier->supplier_email)
                        ->cc($ccUsers)
                        ->send(new PurchaseOrderMail(
                            $date,
                            $organization,
                            $mergeId,
                            $supplier,
                            $allPurchaseOrderDetails,
                            $billToLocationId,
                            $shipToLocationId
                        ));

                    // Update each purchase order with merge_id and mark placed
                    foreach ($poGroup as $purchaseOrder) {
                        $purchaseOrder->merge_id = $mergeId;
                        $purchaseOrder->is_order_placed = true;
                        $purchaseOrder->note = "Order is placed to supplier with merge number: {$mergeId}.";
                        $purchaseOrder->save();
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error('Failed to send PO emails: ' . $e->getMessage());
            $this->handleOrderFailure($poGroup, $e->getMessage());
        }
    }

    private function handleOrderFailure($failedOrders, $errorMessage)
    {
        try {
            $organization = Organization::find($failedOrders->first()->organization_id);

            if (!$organization) {
                $organization = null;
                logger('Could not find organization for failed orders');
            }

            $notificationEmails = config('app.email');
            // Send failure notification
            Mail::to($notificationEmails)
                ->send(new PurchaseOrderFailedMail(
                    $organization,
                    $failedOrders,
                    $errorMessage
                ));

            foreach ($failedOrders as $order) {
                $order->note = "Failed to place order: " . $errorMessage;
                $order->save();
            }
            logger("Sent failure notification for " . $failedOrders->count() . " orders to: " . implode(', ', $notificationEmails));

        } catch (\Exception $e) {
            logger("Failed to send failure notification email: " . $e->getMessage());
        }
    }

}
