<?php

namespace App\Services;

use App\Models\PickingModel;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PickingAuditService
{
    public function logPickingCreation(
        PickingModel $picking,
        $product_id,
        $pickQuantity,
        $unit
    ) {
        try {

            // Create a separate audit log entry for this specific product
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => 'Created',
                'auditable_type' => 'Picking',
                'auditable_id' => $picking->picking_number,
                'old_values' => json_encode([]),
                'new_values' => json_encode([
                    'product_id' => $product_id,
                    'picked_quantity' => $pickQuantity,
                    'unit' => $unit
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