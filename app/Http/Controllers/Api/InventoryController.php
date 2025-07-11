<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\PickingDetailsModel;
use App\Models\PickingModel;
use App\Models\Product;
use App\Models\StockCount;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function inventoryStatus(Request $request)
    {
        try {
            $location_id = $request->input('location_id');
            $per_page = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            $searchTerm = $request->input('search_term', '');

            $stock = StockCount::with(['product.unit.unit', 'product.brand', 'product.supplier', 'product.categories'])
                ->where('organization_id', auth()->user()->organization_id)
                ->where('on_hand_quantity', '>', 0)
                ->where('location_id', $location_id);

            if (!empty($searchTerm)) {
                $stock->whereHas('product', function ($query) use ($searchTerm) {
                    $query->where('product_code', 'like', '%' . $searchTerm . '%')
                        ->orWhere('product_name', 'like', '%' . $searchTerm . '%');
                });
            }

            $paginatedStocks = $stock->paginate($per_page, ['*'], 'page', $page);


            $paginated_data = $paginatedStocks->map(function ($product) {
                $image = null;

                if (str_starts_with($product->product->image, 'http')) {
                    $image = $product->product->image;
                }
                else{
                    $arr = json_decode($product->product->image, true);
                    // Ensure it's a non-empty array and the first item is a valid string
                    if (is_array($arr) && !empty($arr) && !empty($arr[0])) {
                        $image = asset('storage/' . $arr[0]);
                    }
                }

                return [
                    'imageUrl' => $image,
                    'product_id' => $product->product->id,
                    'product_code' => $product->product->product_code,
                    'product_name' => $product->product->product_name,
                    'brand' => $product->product->brand?->brand_name,
                    'category' => $product->product->category?->category_name,
                    'product_cost' => $product->product->cost,
                    'variant_id' => null,
                    'product_qty' => $product->on_hand_quantity,
                    'unit' => $product->product->unit->first()?->unit?->unit_name,
                ];
            });


            return response()->json([
                'success' => true,
                'message' => '',
                'product_data' => $paginated_data,
                'search_term' => $searchTerm,
                'pagination' => [
                    'total' => $paginatedStocks->total(),
                    'per_page' => $paginatedStocks->perPage(),
                    'current_page' => $paginatedStocks->currentPage(),
                    'last_page' => $paginatedStocks->lastPage(),
                    'from' => $paginatedStocks->firstItem(),
                    'to' => $paginatedStocks->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function pickByCode(Request $request)
    {
        try {
            $location_id = $request->input('location_id');
            if ($location_id == null) {
                return response()->json(['success' => false, 'message' => "Kindly Enter warehouse !"]);
            }
            $location = Location::find($location_id);
            if ($location->is_active != true) {
                return response()->json(['success' => false, 'message' => "Your warehouse is not active"]);
            }
            $code = $request->input('code', '');
            $product = Product::where('product_code', $code)->where('organization_id', auth()->user()->organization_id)->first();
            if ($product == null) {
                return response()->json(['success' => true, 'message' => "invalid Product code"]);
            }
            $pro_data = StockCount::where('product_id', $product->id)->where('location_id', $location_id)->first();
            if ($pro_data === null) {
                return response()->json(['success' => true, 'message' => "No product found"]);
            }
            if ($pro_data->on_hand_quantity < 1) {
                return response()->json(['success' => true, 'message' => "Qty is Not Available for " . $product->product_name . " at " . $location->name]);
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
    public function pickUpdate(Request $request)
    {
        try {
            $data = $request->all();

            $location_id = $data['location_id'];
            $product_codes = $data['product_code']; // array of codes
            $qtys = $data['qty']; // array of qtys
            $user_id = $data['user_id'];

            $organization_id = auth()->user()->organization_id;
            $created_by = auth()->user()->id;

            $pickingNumber = PickingModel::generatePickingNumber();

            $total = 0;
            $pickingDetails = [];

            foreach ($product_codes as $index => $code) {
                $product = Product::with('units.unit')->where('product_code', $code)
                    ->where('organization_id', $organization_id)
                    ->first();

                if (!$product) {
                    return response()->json(['success' => false, 'message' => "Product with code {$code} not found"]);
                }

                $qty = $qtys[$index] ?? 0;

                $unit_name = $product->units->first()?->unit->unit_name ?? 'N/A';

                $subtotal = $product->cost * $qty;
                $total += $subtotal;

                $pickingDetails[] = [
                    'product_id' => $product->id,
                    'picking_quantity' => $qty,
                    'picking_unit' => $unit_name,
                    'net_unit_price' => $product->cost,
                    'sub_total' => $subtotal,
                ];
                // Fetch and update StockCount
                $stock = StockCount::where('product_id', $product->id)
                    ->where('location_id', $location_id)
                    ->first();

                if ($stock) {
                    $stock->on_hand_quantity -= $qty;
                    $stock->save();
                }
            }

            // Create Picking
            $picking = PickingModel::create([
                'picking_number' => $pickingNumber,
                'organization_id' => $organization_id,
                'location_id' => $location_id,
                'user_id' => $created_by,
                'total' => $total,
            ]);

            // Create Picking Details
            foreach ($pickingDetails as $detail) {
                $detail['picking_id'] = $picking->id;
                PickingDetailsModel::create($detail);
            }

            return response()->json(['success' => true, 'message' => "Quick pick successful"]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function uploadImage(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            // Validate the image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
            ]);

            // Store the image
            $imagePath = $request->file('image')->store('product_images', 'public');
            // $existingImages = json_decode($product->image ?? '[]', true);
            $existingImages = [];
            $existingImages[] = $imagePath;
            $product->image = json_encode($existingImages);
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image_path' => asset('storage/' . $imagePath),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
