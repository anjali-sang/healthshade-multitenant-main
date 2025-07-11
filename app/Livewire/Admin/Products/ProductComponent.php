<?php

namespace App\Livewire\Admin\Products;

use App\Models\Mycatalog;
use App\Models\Organization;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Log\Logger;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;
 

class ProductComponent extends Component
{
    use WithFileUploads;
    public Product $product;
    public $id;
    public $product_name = '';
    public $product_code = '';
    public $product_supplier_id = '';
    public $product_description = '';
    public $created_by = '';
    public $is_active = true;
    public $updated_by = '';
    public $manufacture_code = '';

    public $units = [];
    public $availableUnits = [];
    public $notifications = [];
    public $baseUnitName = '';
    public $images;
    public $existingImage;

    public $organizations = [];

    public $organization_id = '';
    public $baseUnit = '';
    public function mount()
    {
        $this->product = new Product();
        $this->availableUnits = Unit::where('is_active', true)->get();
        $this->baseUnit = '';
        $this->units[] = [
            'unit_id' => '',
            'operator' => 'multiply',
            'conversion_factor' => 1,
            'is_base_unit' => 0
        ];
        $this->organizations = Organization::where('is_active', true)->get();
    }
    public function updatedBaseUnit($value)
    {
        $this->baseUnit = $value;
        $this->units = [];
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

    public function addEditUnit()
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
    public function removeEditUnit($index)
    {
        if ($index >= 0 && count($this->units) >= 0) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);
        }
    }

    public function createProduct()
    {
        $this->validate([
            'product_name' => [
                'required',
                Rule::unique('products')->where(function($query) {
                    return $query->where('product_name', $this->product_name)
                        ->where('organization_id', auth()->user()->organization_id);
                }),],
            'product_code' => [
                'required',
                Rule::unique('products')->where(function($query) {
                    return $query->where('product_code', $this->product_code)
                        ->where('organization_id', auth()->user()->organization_id);
                }),],
            'product_supplier_id' => 'required',
            'baseUnit' => 'required',
            'images.*' => 'image|max:2048',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $this->validate([
            'units.*.unit_id' => 'nullable|exists:units,id',
            'units.*.operator' => 'required|in:add,subtract,multiply,divide',
            'units.*.conversion_factor' => 'required|numeric|min:0.1',
        ]);
        try {
            DB::beginTransaction();

            // **Store Images First**
            $uploadedImages = [];
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    $uploadedImages[] = $image->store('product_images', 'public');
                }
            } else {
                // **Set default image if no images are uploaded**
                $uploadedImages[] = 'products/default-product.jpg';
            }

            // **Create Product**
            $product = new Product();
            $product->product_name = $this->product_name;
            $product->product_code = $this->product_code;
            $product->product_supplier_id = $this->product_supplier_id;
            $product->product_description = $this->product_description;
            $product->organization_id = $this->organization_id;
            $product->manufacture_code = $this->manufacture_code;
            $product->created_by = auth()->user()->id;
            $product->updated_by = auth()->user()->id;
            $product->image = json_encode($uploadedImages); 

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
            $this->addNotification('Product Created Successfully', 'success');
            $this->dispatch('pg:eventRefresh-products-list-ou8dim-table');
            $this->dispatch('close-modal', 'add-product-modal');
            $this->reset();

        } catch (\Exception | \Throwable $e) {
            DB::rollback();
            Log::error("Error while adding product: " . $e->getMessage(), [
                'product_data' => $this->only(['product_name', 'product_code']),
                'units_data' => $this->units,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/products')->with('error', 'Something went wrong while adding the product. Please try again.');
        }
    }
    #[On('edit-product')]
    public function startedit($rowId)
    {
        $this->reset();
        $productData = Product::findOrFail($rowId);
        $this->id = $rowId;
        $this->product_name = $productData->product_name;
        $this->product_code = $productData->product_code;
        $this->product_supplier_id = $productData->product_supplier_id;
        $this->product_description = $productData->product_description;
        $this->manufacture_code = $productData->manufacture_code;


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

    public function updateProduct()
    {

        // Validate the input data
        $this->validate([
            'product_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_code')->ignore($this->id),
            ],
            'product_name' => 'required|string|max:255',
            'product_supplier_id' => 'required|exists:suppliers,id',
            'manufacture_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'manufacture_code')->ignore($this->id),
            ],
            'product_description' => 'nullable|string|max:1000',
            'baseUnit' => 'required|exists:units,id',
            'units.*.unit_id' => 'nullable|exists:units,id',
            'units.*.operator' => 'nullable|in:multiply,divide',
            'units.*.conversion_factor' => 'nullable|numeric|min:0.01',
            'images.*' => 'nullable|image|max:2048', 
        ]);

        try {
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

            // **Update Product Data**
            $product->update([
                'product_code' => $this->product_code,
                'product_name' => $this->product_name,
                'product_supplier_id' => $this->product_supplier_id,
                'manufacture_code' => $this->manufacture_code,
                'product_description' => $this->product_description,
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
            $this->dispatch('pg:eventRefresh-products-list-ou8dim-table');
            $this->dispatch('close-modal', 'edit-product-modal');
            $this->reset();
            $this->addNotification('Product updated successfully !', 'success');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }


    #[On('toggleMyCatalog')]
    public function toggleMyCatalog($rowId)
    {
        $product = Mycatalog::where('product_id', $rowId)
            ->where('organization_id', auth()->user()->organization_id)
            ->first();

        if ($product) {
            $product->delete();
            $this->addNotification('Product removed from My Catalog', 'error');
        } else {
            Mycatalog::create([
                'organization_id' => auth()->user()->organization_id,
                'product_id' => $rowId,
            ]);
            $this->addNotification('Product added to My Catalog', 'success');
        }
        $this->dispatch('pg:eventRefresh-products-list-ou8dim-table');
        $this->dispatch('pg:eventRefresh-my-products-list-fm2jer-table');
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

    public function deleteProduct()
    {
        $product = Product::find($this->id);

        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }

        $user = auth()->user(); // Get the authenticated user

        if ($user->role_id == 1) { // Super Admin
            $product->update(['is_active' => false, 'is_deleted' => true]);
        } elseif ($user->role_id == 2) { // Other Admins
            $product->update(['is_active' => false]);
        }

        $this->reset();
        $this->dispatch('close-modal', 'edit-product-modal');
        session()->flash('success', 'Product deleted successfully!');
        $this->dispatch('pg:eventRefresh-products-list-ou8dim-table');
    }

    public function render()
    {
        $suppliers = Supplier::all();
        return view('livewire.admin.products.product-component', compact('suppliers'));
    }

    public function downloadSampleCsv()
    {
        $headers = [
            'product_code',
            'product_name',
            'manufacture_code',
            'base_unit_code',
            'product_description',
            'organization_code'
        ];

        $sampleData = [
            [
                'CODE001',
                'Sample Product 1',
                'MFG001',
                'EA',
                'Description for product 1',
                '0002'
            ],
            [
                'CODE002',
                'Sample Product 2',
                'MFG001',
                'DZ',
                'Description for product 2',
                '0002'
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


}
