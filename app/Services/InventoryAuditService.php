<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\PickingModel;
use App\Models\Product;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryAuditService
{
    public function logMyCatalogChangeCreation(
        $rowId,
        $event,
        $message
    ) {
        try {
            $product = Product::where('id', $rowId)->first();
            // Create a separate audit log entry for this specific product
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => $event,
                'auditable_type' => 'Inventory',
                'auditable_id' => $product->product_code,
                'old_values' => json_encode([]),
                'new_values' => json_encode([
                    'product_id' => $product->id,
                    'message' => $message,
                ]),
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
    public function logCartChanges(
        $cartItemId,
        $event,
        $message
    ) {
        try {
            $cart =  Cart::with(['product'])
            ->where('id',         $cartItemId)->first();

            // Create a separate audit log entry for this specific product
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => $event,
                'auditable_type' => 'Cart',
                'auditable_id' => $cart->product->product_code,
                'old_values' => json_encode([]),
                'new_values' => json_encode([
                    'product_id' =>  $cart->product->id,
                    'message' => $message,
                ]),
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
}