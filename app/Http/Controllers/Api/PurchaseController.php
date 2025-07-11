<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\StockCount;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getPurchaseList(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$request->has('location_id')) {
                return response()->json(['success' => false, 'message' => 'Location is required.'], 400);
            }
            $location_id = $request->location_id;
            $location = Location::find($location_id);
            if (!$location) {
                return response()->json(['success' => false, 'message' => 'Your Warehouse is not available. Contact Admin.'], 404);
            }
            $purchase_data = PurchaseOrder::whereIn('status', ['partial', 'ordered'])
                ->where('location_id', $location_id)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($purchase_data->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No data exists.'], 404);
            }
            return response()->json(['success' => true, 'message' => '', 'data' => $purchase_data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getPurchaseOrder(Request $request)
    {
        try {
            $data = $request->all();
            $purchase_id = $data['id'];

            $purchase_order = PurchaseOrder::with('purchasedProducts.product', 'purchasedProducts.unit')->find($purchase_id);
            if ($purchase_order->count() == 0) {
                return response()->json(['success' => true, 'message' => 'No data Found', 'data' => []]);

            }
            return response()->json(['success' => true, 'message' => '', 'purchase_order' => $purchase_order]);

        } catch (\Exception $e) {
            $apiError = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
            return response()->json($apiError, 400);
        }

    }
    public function updatePurchaseOrder(Request $request)
    {
        try {
            $data = $request->except('document');
            $id = $data['id'] ?? 0;
            $apiResponse = ['success' => true, 'message' => 'Purchase updated successfully', 'data' => null];
            $lims_purchase_data = PurchaseOrder::find($id);
            if ($lims_purchase_data == null) {
                return response()->json(['success' => false, 'message' => "Invalid Purchase id $id", 'data' => null]);
            }
            $lims_product_purchase_data = PurchaseOrderDetail::where('purchase_order_id', $id)->get();

            if ($lims_product_purchase_data == null) {
                return response()->json(['success' => false, 'message' => "There is no product in this PO"]);
            }
            $product_ids = $data['product_id'];
            $recieveds = $data['recieved'];

            foreach ($product_ids as $key => $pro_id) {

                $lims_product_data = Product::find($pro_id);
                if ($lims_product_data == null) {
                    return response()->json(['success' => false, 'message' => "Invalid Product id $pro_id", 'data' => null]);
                }

                $product_purchase_data = PurchaseOrderDetail::where('purchase_order_id', $id)->where('product_id', $pro_id)->first();
                $this->updateProductStock($pro_id, $product_purchase_data->unit_id, $recieveds[$key], $lims_purchase_data->location_id);
                $product_purchase_data->received_quantity += $recieveds[$key];
                $product_purchase_data->save();
            }

            $allCompleted = $lims_purchase_data->purchasedProducts
                ->every(function ($product) {
                    return $product->received_quantity >= $product->quantity;
                });

            $anyReceived = $lims_purchase_data->purchasedProducts
                ->some(function ($product) {
                    return $product->received_quantity > 0;
                });

            $status = $allCompleted ? 'completed' : ($anyReceived ? 'partial' : 'pending');

            $lims_purchase_data->status = $status;
            $lims_purchase_data->save();
            return response()->json($apiResponse);
        } catch (\Exception $e) {
            $apiError = [
                'success' => false,
                'message' => $e->getMessage() . ' (Line ' . $e->getLine() . ')',
                'data' => [],
            ];
            return response()->json($apiError, 400);
        }
    }

    public function updateProductStock($productId, $unitId, $quantity, $location)
    {
        try {
            $stockCount = StockCount::firstOrNew([
                'product_id' => $productId,
                'location_id' => $location,
                'organization_id' => auth()->user()->organization_id,
            ]);

            $convertedQuantity = $this->convertQuantityToBaseUnit($productId, $unitId, $quantity);

            $stockCount->on_hand_quantity += $convertedQuantity;
            $stockCount->save();

            return true;
        } catch (\Exception $e) {
            \Log::error('Stock update failed: ' . $e->getMessage());
            throw new \Exception('Failed to update stock: ' . $e->getMessage());
        }
    }
    public function convertQuantityToBaseUnit($productId, $unitId, $quantity)
    {
        // Get the product's base unit and conversion
        $productUnit = ProductUnit::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->first();

        if (!$productUnit) {
            throw new \Exception('Unit conversion not found for this product');
        }
        if ($productUnit->is_base_unit) {
            return $quantity;
        }
        return $quantity * $productUnit->conversion_factor;
    }

}
