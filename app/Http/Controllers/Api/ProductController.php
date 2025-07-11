<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Product;
use App\Models\StockCount;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function pickByCode(Request $request)
    {
        try {
            $location_id = $request->input('warehouse_id');
            if ($location_id == null) {
                return response()->json(['success' => false, 'message' => "Kindly Enter warehouse !"]);
            }
            $location = Location::find($location_id);
            if($location->is_active != true){
                return response()->json(['success' => false, 'message' => "Your warehouse is not active" ]);
            }
            $code = $request->input('code', '');
            $product = Product::where('product_code', $code)->where('organization_id',auth()->user()->organization_id)->first();
            if ($product == null) {
                return response()->json(['success' => true, 'message' => "invalid Product code"]);
            }
            $pro_data = StockCount::where('product_id', $product->id)->where('location_id', $location_id)->first();
            if ($pro_data === null) {
                return response()->json(['success' => true, 'message' => "No product found" ]);
            }
            if ($pro_data->on_hand_quantity < 1) {
                return response()->json(['success' => true, 'message' => "Qty is Not Available for ".$product->product_name." at ".$location->name ]);
            }
            $onhand_qty = $pro_data->on_hand_quantity;

            $product->onhand_qty = $onhand_qty;
            return response()->json(['success' => true, 'message' => '', 'data' => $product]);

        } catch (\Exception $e) {
            $lineNumber = $e->getLine();
            $apiError = $e->getMessage();
            $apiErrorMessage = "Error on line $lineNumber: $apiError";
            return response()->json(['success' => false, 'message' => $apiErrorMessage]);
        }
    }
}
