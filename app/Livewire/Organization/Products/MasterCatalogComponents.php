<?php

namespace App\Livewire\Organization\Products;

use App\Models\AlertParTacking;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Location;
use App\Models\Mycatalog;
use App\Models\Organization;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockCount;
use App\Models\Supplier;
use App\Models\Unit;
use DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;

class MasterCatalogComponents extends Component
{
    use WithFileUploads;
    public Product $product;
    public $id = '';
    public $brands = [];
    public $brand_id;
    public $product_id = '';
    public $category_id;
    public $product_name = '';
    public $product_code = '';
    public $product_supplier_id = '';

    public $product_description = '', $product_price = '', $product_cost = '', $is_deleted = false, $created_by = '', $updated_by = '', $manufacture_code = '', $is_approved = false, $approved_by = '', $selectedCategory = null, $units = [], $availableUnits = [], $notifications = [], $baseUnitName = '', $locations = [], $locationData = [], $catalogCount, $product_base_unit, $baseUnit = '', $images, $price, $cost, $organization, $existingImage, $length, $width, $height, $weight;

    public $isBatch = '';
    public $brand_search = '';
    public $selected_brand_name = '';
    public $show_dropdown = false;

    public $filtered_brands = [];

    public function mount()
    {
        $this->brands = Brand::where('organization_id', auth()->user()->organization_id)->where('brand_is_active', true)->orderBy('brand_name', 'asc')
            ->get();
        $this->filtered_brands = $this->brands;


        $this->catalogCount = MyCatalog::where('mycatalogs.organization_id', auth()->user()->organization_id)
            ->leftJoin('products', 'mycatalogs.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->count();

        $this->organization = Organization::where('id', auth()->user()->organization_id)->first();

        $this->product = new Product();
        $this->availableUnits = Unit::where('is_active', true)->get();
        $this->baseUnit = '';
        $this->units[] = [
            'unit_id' => '',
            'operator' => 'multiply',
            'conversion_factor' => 1,
            'is_base_unit' => 0
        ];
        $this->locations = Location::where('is_active', true)->where('org_id', auth()->user()->organization_id)->get();
        foreach ($this->locations as $location) {
            $this->locationData[$location->id] = [
                'alert_quantity' => $location->alert_quantity ?? 3,
                'par_quantity' => $location->par_quantity ?? 10,
            ];
        }

    }
    public function updatedBrandSearch()
    {


        if (empty($this->brand_search)) {
            $this->filtered_brands = $this->brands;
            $this->brand_id = '';
            $this->selected_brand_name = '';
            $this->show_dropdown = false;
        } else {
            $this->show_dropdown = true;
            $this->filtered_brands = $this->brands->filter(function ($brand) {
                return stripos($brand->brand_name, $this->brand_search) !== false;
            });
        }

    }
    public function showDropdown()
    {
        $this->show_dropdown = true;
        if (empty($this->brand_search)) {
            $this->filtered_brands = $this->brands;
        }
    }

    public function hideDropdown()
    {
        // Small delay to allow clicking on dropdown items
        $this->dispatch('hide-dropdown-delayed');
    }

    public function selectBrand($brandId, $brandName)
    {
        $this->brand_id = $brandId;
        $this->brand_search = $brandName;
        $this->selected_brand_name = $brandName;
        $this->show_dropdown = false;
    }
    public function updatedBaseUnit($value)
    {
        $this->baseUnit = $value;
        $this->units = [];
    }
    public function createProduct()
    {
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('add_products') && $user->role_id > 2) {
            $this->addNotification('You don\'t have permission to add Products!', 'error');
            return;
        }
        $this->validate([
            'product_name' => [
                'required',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('organization_id', auth()->user()->organization_id);
                }),
            ],
            'product_code' => [
                'required',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('organization_id', auth()->user()->organization_id);
                }),
            ],
            'product_supplier_id' => 'required',
            'baseUnit' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'cost' => 'required|numeric|min:0.1',
        ]);

        $this->validate([
            'units.*.unit_id' => 'nullable|exists:units,id',
            'units.*.operator' => 'required|in:add,subtract,multiply,divide',
            'units.*.conversion_factor' => 'required|numeric|min:0.1',
        ]);
        try {
            DB::beginTransaction();
            $this->price = $this->price != '' ? $this->price : $this->cost;
            // **Store Images First**
            $uploadedImages = [];
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    $uploadedImages[] = $image->store('product_images', 'public');
                }
            }
            if ($this->isBatch == 'true') {
                $isBatch = true;
            } else {
                $isBatch = false;
            }

            if ($this->brand_search != '') {
                $brand = Brand::where('organization_id', auth()->user()->organization_id)
                    ->where('brand_name', 'like', '%' . $this->brand_search . '%')
                    ->first();
                if ($brand) {
                    $this->brand_id = $brand->id;
                } else {
                    // If brand doesn't exist, create a new one
                    $newBrand = Brand::create([
                        'brand_name' => $this->brand_search,
                        'organization_id' => auth()->user()->organization_id,
                        'brand_is_active' => true,
                    ]);
                    $this->brand_id = $newBrand->id;
                }
            }

            // **Create Product**
            $product = new Product();
            $product->product_name = $this->product_name;
            $product->brand_id = $this->brand_id;
            $product->product_code = $this->product_code;
            $product->product_supplier_id = $this->product_supplier_id;
            $product->product_description = $this->product_description;
            $product->manufacture_code = $this->manufacture_code;
            $product->created_by = auth()->user()->id;
            $product->updated_by = auth()->user()->id;
            $product->image = json_encode($uploadedImages);
            $product->organization_id = auth()->user()->organization_id;
            $product->category_id = $this->category_id;
            $product->cost = $this->cost;
            $product->price = $this->price;
            $product->has_expiry_date = $isBatch;
            $product->weight = $this->weight ?? 0;
            $product->length = $this->length ?? 0;
            $product->width = $this->width ?? 0;
            $product->height = $this->height ?? 0;

            if (!$product->save()) {
                throw new \Exception('Failed to save product');
            }

            $productUnits = [];

            $productUnits[] = [
                'product_id' => $product->id,
                'unit_id' => $this->baseUnit,
                'is_base_unit' => 1,
                'operator' => 'multiply',
                'conversion_factor' => 1.00,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            foreach ($this->units as $unit) {
                if (empty($unit['unit_id'])) {
                    continue;
                }
                $productUnits[] = [
                    'product_id' => $product->id,
                    'unit_id' => $unit['unit_id'],
                    'is_base_unit' => 0,
                    'operator' => strval($unit['operator']),
                    'conversion_factor' => floatval($unit['conversion_factor']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach ($productUnits as $unit) {
                if (!DB::table('product_units')->insert($unit)) {
                    throw new \Exception('Failed to save product unit');
                }
            }
            DB::commit();

            $this->dispatch('pg:eventRefresh-master-catalog-list-wvwykb-table');
            $this->dispatch('close-modal', 'add-product-modal');
            $this->reset([
                'product_name',
                'product_code',
                'product_supplier_id',
                'product_description',
                'manufacture_code',
                'cost',
                'price',
                'category_id',
                'baseUnit',
                'units',
                'images',
                'isBatch',
                'brand_search',
                'weight',
                'length',
                'width',
                'height'
            ]);

        } catch (\Exception | \Throwable $e) {
            DB::rollback();
            Log::error("Error while adding product: " . $e->getMessage(), [
                'product_data' => $this->only(['product_name', 'product_code']),
                'units_data' => $this->units,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/organization/catalog')->with('error', 'Something went wrong while adding the product. Please try again.');
        }
    }
    public function addUnit()
    {
        $this->units[] = [
            'unit_id' => '',
            'operator' => 'multiply',
            'conversion_factor' => 1,
            'is_base_unit' => 0
        ];
    }

    public function removeUnit($index)
    {
        if ($index !== 0 && count($this->units) > 1) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);
        }
    }

    #[On('toggleMyCatalog')]
    public function toggleMyCatalog($rowId)
    {
        $this->product_id = $rowId;
        $org_id = auth()->user()->organization_id;
        // Get all active locations for the organization
        $locations = Location::where('is_active', true)
            ->where('org_id', $org_id)
            ->get();


        // Check if product is already in My Catalog
        $product = Mycatalog::where('product_id', $rowId)
            ->where('organization_id', $org_id)
            ->first();

        if ($product) {
            // Remove product from My Catalog
            $product->delete();
            foreach ($locations as $location) {
                $stock = StockCount::where('organization_id', $org_id)
                    ->where('product_id', $this->product_id)
                    ->where('location_id', $location->id)
                    ->where('on_hand_quantity', 0)
                    ->first();

                if ($stock) {
                    $stock->delete();
                }
            }
            $event = 'Removed';
            $message = "Following Product has been<br/>removed from the catalog";
            $auditService = app(\App\Services\InventoryAuditService::class);
            $auditService->logMyCatalogChangeCreation(
                $rowId,
                $event,
                $message
            );
            $this->addNotification('Product removed from My Catalog', 'error');
        } else {
            $product = Mycatalog::create(
                [
                    'product_id' => $rowId,
                    'organization_id' => $org_id,
                ]
            );

            logger()->info('Product added to My Catalog', [
                'product_id' =>$rowId,
                'organization_id' => $org_id,
            ]);

            // Add product to inventory with 0 on-hand quantity for each location
            foreach ($locations as $location) {
                $exists = StockCount::where('organization_id', $org_id)
                    ->where('product_id', $this->product_id)
                    ->where('location_id', $location->id)
                    ->exists();

                if (!$exists) {
                    StockCount::create([
                        'organization_id' => $org_id,
                        'product_id' => $this->product_id,
                        'location_id' => $location->id,
                        'on_hand_quantity' => 0,
                    ]);
                }
            }

        }
        $catalog = MyCatalog::where('mycatalogs.organization_id', auth()->user()->organization_id)
            ->leftJoin('products', 'mycatalogs.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->count();
        if ($catalog == 0)
            redirect('organization/catalog');

        $this->dispatch('pg:eventRefresh-master-catalog-list-wvwykb-table');
        $this->dispatch('pg:eventRefresh-my-catalog-list-cnalxn-table');
    }
    // public function toggleMyCatalog($rowId)
    // {
    //     $this->product_id = $rowId;
    //     $org_id = auth()->user()->organization_id;

    //     // Check if product is already in My Catalog
    //     $product = Mycatalog::where('product_id', $rowId)
    //         ->where('organization_id', $org_id)
    //         ->first();

    //     if ($product) {
    //         // Remove product from My Catalog
    //         $product->delete();
    //         $event = 'Removed';
    //         $message = "Following Product has been<br/>removed from the catalog";
    //         $auditService = app(\App\Services\InventoryAuditService::class);
    //         $auditService->logMyCatalogChangeCreation(
    //             $rowId,
    //             $event,
    //             $message
    //         );
    //         $this->addNotification('Product removed from My Catalog', 'error');
    //         $catalog = MyCatalog::where('organization_id', auth()->user()->organization_id)
    //             ->leftJoin('products', 'mycatalogs.product_id', '=', 'products.id')
    //             ->where('products.is_active', true)
    //             ->count();
    //         if ($catalog == 0)
    //             redirect('organization/catalog');
    //         $this->dispatch('pg:eventRefresh-master-catalog-list-wvwykb-table');
    //         $this->dispatch('pg:eventRefresh-my-products-list-fm2jer-table');
    //     } else {
    //         $unit = ProductUnit::where('product_id', $rowId)
    //             ->where('is_base_unit', true)
    //             ->first();

    //         $this->product_base_unit = $unit ? $unit->unit->unit_name : null;
    //         $this->dispatch('open-modal', 'fill-product-form');
    //     }
    // }

    #[On('edit-product')]
    public function startedit($rowId)
    {
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('edit_products') && $user->role_id > 2) {
            $this->addNotification('You don\'t have permission to Edit Products!', 'error');
            return;
        }
        $this->reset();
        $productData = Product::findOrFail($rowId);
        $this->organization = Organization::where('id', auth()->user()->organization_id)->where('is_active', operator: '1')
            ->orderBy('category_name')
            ->get();
        $this->brands = Brand::where('organization_id', auth()->user()->organization_id)->where('brand_is_active', true) ->orderBy('brand_name')
            ->get();
        $this->id = $rowId;
        $this->product_name = $productData->product_name;
        $this->brand_id = $productData->brand_id;
        $this->product_code = $productData->product_code;
        $this->product_supplier_id = $productData->product_supplier_id;
        $this->product_description = $productData->product_description;
        $this->manufacture_code = $productData->manufacture_code;
        $this->cost = $productData->cost;
        $this->price = $productData->price;
        $this->category_id = $productData->category_id;
        $this->isBatch = $productData->has_expiry_date ? true : false;
        $this->weight = $productData->weight ?? 0;
        $this->length = $productData->length ?? 0;
        $this->width = $productData->width ?? 0;
        $this->height = $productData->height ?? 0;
        $this->brand_search = $this->brands->where('id', $this->brand_id)->first()->brand_name ?? '';

        $productUnits = ProductUnit::where('product_id', $rowId)->get();
        $baseUnitData = $productUnits->where('is_base_unit', 1)->first();
        $this->baseUnit = $baseUnitData ? $baseUnitData->unit_id : null;
        $this->availableUnits = Unit::where('is_active', true)->get();
        $this->units = [];
        // Add base unit as the first element
        // if ($this->baseUnit) {
        //     $this->units[0] = [
        //         'unit_id' => $this->baseUnit,
        //         'operator' => null,
        //         'conversion_factor' => null,
        //         'is_base_unit' => 1
        //     ];
        // }

        // Add other units
        $index = 0;
        foreach ($productUnits->where('is_base_unit', 0) as $productUnit) {
            $this->units[$index] = [
                'unit_id' => $productUnit->unit_id,
                'operator' => $productUnit->operator,
                'conversion_factor' => $productUnit->conversion_factor,
                'is_base_unit' => 0
            ];
            $index++;
        }


        // Set the existing image
        $existingImages = json_decode($productData->image, true) ?? [];
        $this->existingImage = $existingImages[0] ?? null;

        $this->dispatch('open-modal', 'edit-product-modal');
    }

    public function addEditUnit()
    {
        $this->units[] = [
            'unit_id' => '',
            'operator' => 'multiply',
            'conversion_factor' => 1,
            'is_base_unit' => 0
        ];
    }
    public function removeEditUnit($index)
    {
        if ($index >= 0 && count($this->units) >= 0) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);
        }
    }
    public function updateProduct()
    {

        // Validate the input 

        $this->validate([
            'product_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_code')
                    ->ignore($this->id)
                    ->where(fn($query) => $query
                        ->where('organization_id', auth()->user()->organization_id)
                        ->where('is_active', true)),
            ],
            'product_name' => 'required|string|max:255',
            'product_supplier_id' => 'required|string|max:255',
            'manufacture_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'manufacture_code')
                    ->ignore($this->id)
                    ->where(fn($query) => $query
                        ->where('organization_id', auth()->user()->organization_id)
                        ->where('is_active', true)),
            ],
            'product_description' => 'nullable|string|max:1000',
            'baseUnit' => 'required|exists:units,id',

            'units.*.unit_id' => 'nullable|exists:units,id',
            'units.*.operator' => 'nullable|in:multiply,divide',
            'units.*.conversion_factor' => 'nullable|numeric|min:0.01',

            'images.*' => 'nullable|image|max:2048',
        ]);


        try {

            if ($this->brand_search != '') {
                $brand = Brand::where('organization_id', auth()->user()->organization_id)
                    ->where('brand_name', 'like', '%' . $this->brand_search . '%')
                    ->first();
                if ($brand) {
                    $this->brand_id = $brand->id;
                } else {
                    // If brand doesn't exist, create a new one
                    $newBrand = Brand::create([
                        'brand_name' => $this->brand_search,
                        'organization_id' => auth()->user()->organization_id,
                        'brand_is_active' => true,
                    ]);
                    $this->brand_id = $newBrand->id;
                }
            }
            $product = Product::findOrFail($this->id);

            // **Handle Image Updates**
            $existingImages = json_decode($product->image, true) ?? [];
            $uploadedImages = [];

            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    $uploadedImages[] = $image->store('product_images', 'public');
                }
            }

            // If new images are uploaded, replace the old ones; otherwise, keep existing
            $product->image = !empty($uploadedImages) ? json_encode($uploadedImages) : json_encode($existingImages);
            if ($this->isBatch == 'true') {
                $isBatch = true;
            } else {
                $isBatch = false;
            }
            // **Update Product Data**
            $product->update([
                'brand_id' => $this->brand_id,
                'product_code' => $this->product_code,
                'product_name' => $this->product_name,
                'product_supplier_id' => $this->product_supplier_id,
                'manufacture_code' => $this->manufacture_code,
                'product_description' => $this->product_description,
                'cost' => $this->cost,
                'price' => $this->price ?? $this->cost,
                'category_id' => $this->category_id,
                'updated_by' => auth()->user()->id,
                'has_expiry_date' => $isBatch,
                'weight' => $this->weight ?? 0,
                'length' => $this->length ?? 0,
                'width' => $this->width ?? 0,
                'height' => $this->height ?? 0,
            ]);

            // **Manage Product Units**
            ProductUnit::where('product_id', $product->id)->delete();
            ProductUnit::create([
                'product_id' => $product->id,
                'unit_id' => $this->baseUnit,
                'operator' => 'multiply',
                'conversion_factor' => 1.00,
                'is_base_unit' => true
            ]);

            foreach ($this->units as $unit) {
                ProductUnit::create([
                    'product_id' => $product->id,
                    'unit_id' => $unit['unit_id'],
                    'operator' => $unit['operator'],
                    'conversion_factor' => $unit['conversion_factor'],
                    'is_base_unit' => false
                ]);
            }

            // **Emit success message and close modal**
            $this->dispatch('pg:eventRefresh-master-catalog-list-wvwykb-table');
            $this->dispatch('close-modal', 'edit-product-modal');
            $this->reset();
            $this->addNotification('Product updated successfully !', 'success');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }

    // public function downloadSampleCsv()
    // {
    //     $headers = [
    //         'supplier',
    //         'product_code',
    //         'product_name',
    //         'base_unit',
    //         'product_description',
    //         'product_cost',
    //         'category',
    //         'alert_quantity',
    //         'par_quantity'
    //     ];

    //     // Fetch products with their base unit
    //     $products = DB::table('products')
    //         ->join('product_units', 'products.id', '=', 'product_units.product_id')
    //         ->join('units', 'product_units.unit_id', '=', 'units.id')
    //         ->join('suppliers','suppliers.id','=','products.product_supplier_id')
    //         ->where('product_units.is_base_unit', true)
    //         ->select(
    //             'products.product_code',
    //             'products.product_name',
    //             'units.unit_name as base_unit',
    //             'products.product_description',
    //             'suppliers.supplier_name'
    //         )
    //         ->get();

    //     // Prepare CSV content
    //     $csv = implode(',', $headers) . "\n";

    //     foreach ($products as $product) {
    //         $csvRow = [
    //             $product->supplier_name,
    //             $product->product_code,
    //             $product->product_name,
    //             $product->base_unit,
    //             $product->product_description,
    //             '', // Empty product_cost
    //             '', // Empty category
    //             '3', // Empty alert_quantity
    //             '10'  // Empty par_quantity
    //         ];

    //         $csv .= implode(',', array_map(function ($value) {
    //             // Escape any commas in the values
    //             return str_contains($value, ',') ? '"' . $value . '"' : $value;
    //         }, $csvRow)) . "\n";
    //     }

    //     return response()->streamDownload(function () use ($csv) {
    //         echo $csv;
    //     }, 'master_catalog_import.csv');
    // }

    public function downloadSampleCsv()
    {
        $headers = [
            'product_code',
            'product_name',
            'manufacture_code',
            'base_unit_code',
            'product_description',
            'category',
            'cost',
            'price',
        ];

        $sampleData = [
            [
                'CODE001',
                'Sample Product 1',
                'MFG001',
                'EA',
                'Description for product 1',
                'Office supplies',
                '120',
                '150',
            ],
            [
                'CODE002',
                'Sample Product 2',
                'MFG001',
                'DZ',
                'Description for product 2',
                'Office supplies',
                '120',
                '150',
            ],
        ];
        $csv = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $csv .= implode(',', $row) . "\n";
        }
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'sample_products_import.csv');
    }

    public function deleteProduct()
    {
        $product = Product::where('id', $this->id)->first();
        if ($product) {
            $product->is_active = false;
            $product->save();
            $this->dispatch('pg:eventRefresh-master-catalog-list-wvwykb-table');
            $this->dispatch('close-modal', 'edit-product-modal');
            $this->addNotification('Product deleted successfully !', 'success');
        } else {
            $this->addNotification('Product not found !', 'error');
            $this->addNotification('Product deleted successfully !', 'success');
        }
    }

    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ]);

        // Limit to a maximum of 3-5 notifications if needed
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        }));
    }

    public function render()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $categories = Category::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', operator: '1')
            ->orderBy('category_name', 'asc')
            ->get();
        return view('livewire.organization.products.master-catalog-components', compact('suppliers', 'categories'));
    }
}