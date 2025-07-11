<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderAuditService
{
    /**
     * Log the creation of a purchase order with separate entries for each product
     *
     * @param PurchaseOrder $purchaseOrder
     * @param array $productDetails Product details to log
     * @return bool
     */
    public function logPurchaseOrderCreation(PurchaseOrder $purchaseOrder, array $productDetails = [])
    {
        try {
            // Base purchase order information without product details
            $poBaseInfo = [
                'purchase_order_number' => $purchaseOrder->purchase_oder_number,
                'supplier_id' => $purchaseOrder->supplier_id,
                'organization_id' => $purchaseOrder->organization_id,
                'location_id' => $purchaseOrder->location_id,
                'bill_to_location_id' => $purchaseOrder->bill_to_location_id,
                'ship_to_location_id' => $purchaseOrder->ship_to_location_id,
                'status' => $purchaseOrder->status,
                'total' => $purchaseOrder->total,
            ];

            // Create a separate audit log entry for this specific product
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => 'created',
                'auditable_type' => 'Purchase Order',
                'auditable_id' => $purchaseOrder->purchase_oder_number,
                'old_values' => json_encode([]),
                'new_values' => json_encode(array_merge($poBaseInfo, $productDetails)),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id' => auth()->user()->organization_id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log purchase order product audit: ' . $e->getMessage());
            return false;
        }
    }

    public function logProductReceiving(PurchaseOrder $purchaseOrder, $productId, $receivedQuantity, $newReceivedQuantity = 0)
    {
        try {
            $details = PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)
                ->where('product_id', $productId)
                ->first();

            if (!$details) {
                Log::error("Product ID {$productId} not found in purchase order {$purchaseOrder->id}");
                return false;
            }

            // Old and new values for audit
            $oldValues = ['product_id'=>$details->product_id,'received_quantity' => $receivedQuantity];
            $newValues = ['product_id'=>$details->product_id,'received_quantity' => $newReceivedQuantity];

            // Insert into audit table
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => 'Received',
                'auditable_type' => 'Purchase Order',
                'auditable_id' => $purchaseOrder->purchase_oder_number,
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($newValues),
                'organization_id' => auth()->user()->organization_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log product receiving: ' . $e->getMessage());
            return false;
        }
    }


}