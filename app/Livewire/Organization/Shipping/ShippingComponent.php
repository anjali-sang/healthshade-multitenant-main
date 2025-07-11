<?php

namespace App\Livewire\Organization\Shipping;

use App\Models\ProductUnit;
use App\Models\Shipment;
use App\Models\ShipmentProducts;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Product;
use App\Models\BatchInventory;
use App\Models\StockCount;
use App\Models\Unit;
use Illuminate\Log\Logger;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\FunctionPrefix;

class ShippingComponent extends Component
{
    public $editMode = false;
    public $shipmentId = null;

    // Shipment properties
    public $shipment_number = '';
    public $customer_id = '';
    public $location_id;
    public $total_quantity = 0;
    public $total_price = 0;
    public $grand_total = 0;

    // Shipment products
    public $shipmentProducts = [];

    // Available options
    public $customers = [];
    public $locations = [];
    public $products = [];
    public $units = [];

    // Loading states
    public $isLoading = false;
    public $isSaving = false;
    public $products_search = '';
    public $selected_product_name = '';
    public $show_dropdown = false;

    public $filtered_products = [];

    public $show_dropdown_index = null;

    public $viewMode = false;

    public $prepareShipment = false;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'location_id' => 'required|exists:locations,id',
        'shipmentProducts' => 'required|array|min:1',
        'shipmentProducts.*.product_id' => 'required|exists:products,id',
        'shipmentProducts.*.quantity' => 'required|numeric|min:1',
        'shipmentProducts.*.shipment_unit_id' => 'required|exists:units,id',
        'shipmentProducts.*.net_unit_price' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'customer_id.required' => 'Please select a customer.',
        'customer_id.exists' => 'Selected customer is invalid.',
        'location_id.required' => 'Please select a location.',
        'location_id.exists' => 'Selected location is invalid.',
        'shipmentProducts.required' => 'Please add at least one product.',
        'shipmentProducts.min' => 'Please add at least one product.',
        'shipmentProducts.*.product_id.required' => 'Please select a product.',
        'shipmentProducts.*.product_id.exists' => 'Selected product is invalid.',
        'shipmentProducts.*.quantity.required' => 'Please enter quantity.',
        'shipmentProducts.*.quantity.min' => 'Quantity must be at least 1.',
        'shipmentProducts.*.quantity.numeric' => 'Quantity must be a valid number.',
        'shipmentProducts.*.shipment_unit_id.required' => 'Please select a unit.',
        'shipmentProducts.*.shipment_unit_id.exists' => 'Selected unit is invalid.',
        'shipmentProducts.*.net_unit_price.required' => 'Please enter unit price.',
        'shipmentProducts.*.net_unit_price.numeric' => 'Unit price must be a valid number.',
        'shipmentProducts.*.net_unit_price.min' => 'Unit price cannot be negative.',
    ];
    public function updatedProductsSearch()
    {
        if (empty($this->products_search)) {
            $this->filtered_products = $this->products;
            $this->product_id = '';
            $this->selected_brand_name = '';
            $this->show_dropdown = false;
        } else {
            $this->show_dropdown = true;
            $this->filtered_products = $this->products->filter(function ($product) {
                return stripos($product->product_name, $this->products_search) !== false;
            });
        }

    }
    public function showDropdown($index)
    {
        $this->show_dropdown_index = $index;
        $this->filtered_products = $this->products;
    }

    public function hideDropdown()
    {
        $this->dispatch('hide-dropdown-delayed');
    }
    public function mount()
    {
        $this->loadOptions();
        $this->addProductRow();
    }

    public function selectProduct($index, $productId, $productName)
    {
        $this->shipmentProducts[$index]['product_id'] = $productId;
        $this->shipmentProducts[$index]['product_search'] = $productName;
        $this->shipmentProducts[$index]['batch_id'] = '';
        $this->shipmentProducts[$index]['available_batches'] = $this->getBatchesForProduct($productId);
        $this->shipmentProducts[$index]['total_price'] = 0;

        $unitData = $this->getUnitsForProduct($productId)->first();
        $this->shipmentProducts[$index]['shipment_unit_id'] = $unitData['id'] ?? null;
        $this->shipmentProducts[$index]['shipment_unit'] = $unitData['unit'] ?? null;
        $this->calculateRowTotal($index);

        $this->show_dropdown_index = null;
        $this->filtered_products = collect();
    }
    public function getUnitsForProduct($productId)
    {
        if (!$productId)
            return [];

        try {
            return ProductUnit::where('product_id', $productId)
                ->where('is_base_unit', true)
                ->with('unit')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => optional($product->unit)->id,
                        'unit' => optional($product->unit)->unit_name,
                        'code' => optional($product->unit)->unit_code,
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error fetching batches for product: ' . $e->getMessage());
            return [];
        }
    }
    public function loadOptions()
    {
        try {
            $this->customers = Customer::select('id', 'customer_name', 'customer_email')
                ->where('customer_is_active', true)
                ->orderBy('customer_name')
                ->get();

            $this->locations = Location::select('id', 'name')
                ->where('is_active', true)
                ->where('org_id',auth()->user()->organization_id)
                ->orderBy('name')
                ->get();

        } catch (\Exception $e) {
            Log::error('Error loading options: ' . $e->getMessage());
            session()->flash('error', 'Error loading form options. Please refresh the page.');
        }
    }
    public function updatedLocationId()
    {
        $this->reset('shipmentProducts');
        $this->products = Product::select('id', 'product_name', 'product_code')
            ->where('is_active', true)
            ->orderBy('product_name')
            ->get();
    }

    #[\Livewire\Attributes\On('view')]
    public function viewShippingModal($shipmentId)
    {
        $this->resetForm();

        if ($shipmentId) {
            $this->viewMode = true;
            $this->shipmentId = $shipmentId;
            $this->loadOptions();
            $this->loadShipment($shipmentId);
        }
        $this->dispatch('open-modal', 'shipment-modal');
    }
    public function openShipmentModal($shipmentId = null)
    {
        $this->resetForm();

        if ($shipmentId) {
            $this->viewMode = false;
            $this->editMode = true;
            $this->shipmentId = $shipmentId;
            $this->loadOptions();
            $this->loadShipment($shipmentId);
        } else {
            $this->editMode = false;
            $this->viewMode = false;
            $this->generateShipmentNumber();
            $this->dispatch('open-modal', 'shipment-modal');
        }
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'shipment-modal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->shipment_number = '';
        $this->customer_id = '';
        $this->location_id = '';
        $this->total_quantity = 0;
        $this->total_price = 0;
        $this->grand_total = 0;
        $this->shipmentProducts = [];
        $this->editMode = false;
        $this->shipmentId = null;
        $this->isLoading = false;
        $this->isSaving = false;
        $this->resetValidation();
        $this->addProductRow();
    }

    public function loadShipment($shipmentId)
    {
        try {
            $this->isLoading = true;

            $shipment = Shipment::with(['shipmentProducts.product', 'shipmentProducts.batch'])
                ->findOrFail($shipmentId);

            $this->shipment_number = $shipment->shipment_number;
            $this->customer_id = $shipment->customer_id;
            $this->location_id = $shipment->location_id;
            $this->total_quantity = $shipment->total_quantity;
            $this->total_price = $shipment->total_price;
            $this->grand_total = $shipment->grand_total;

            if ($this->location_id) {
                $this->products = Product::select('products.id', 'products.product_name', 'products.product_code')
                    ->leftJoin('stock_counts', function ($join) {
                        $join->on('stock_counts.product_id', '=', 'products.id')
                            ->where('stock_counts.location_id', '=', $this->location_id);
                    })
                    ->where('stock_counts.on_hand_quantity', '>', 0)
                    ->where('products.is_active', true)
                    ->orderBy('products.product_name')
                    ->get();
            } else {
                $this->products = Product::select('products.id', 'products.product_name', 'products.product_code')
                    ->where('products.is_active', true)
                    ->orderBy('products.product_name')
                    ->get();
            }



            $this->shipmentProducts = $shipment->shipmentProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_search' => $product->product->product_name,
                    'product_id' => $product->product_id,
                    'batch_id' => $product->batch_id,
                    'quantity' => $product->quantity,
                    'shipment_unit_id' => $product->shipment_unit_id,
                    'shipment_unit' => $product->unit->unit_name,
                    'net_unit_price' => $product->net_unit_price,
                    'total_price' => $product->total_price,
                    'available_batches' => $this->getBatchesForProduct($product->product_id),
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Error loading shipment: ' . $e->getMessage());
            session()->flash('error', 'Error loading shipment data.');
            $this->closeModal();
        } finally {
            $this->isLoading = false;
        }
    }

    public function generateShipmentNumber()
    {
        try {
            $lastShipment = Shipment::latest('id')->first();
            $nextNumber = $lastShipment ? $lastShipment->id + 1 : 1;
            $this->shipment_number = 'SHP-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            Log::error('Error generating shipment number: ' . $e->getMessage());
            $this->shipment_number = 'SHP-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        }
    }

    public function addProductRow()
    {
        $this->shipmentProducts[] = [
            'product_id' => '',
            'product_search' => '',
            'batch_id' => '',
            'quantity' => 1,
            'shipment_unit_id' => '',
            'net_unit_price' => 0,
            'total_price' => 0,
            'available_batches' => [],
        ];
    }
    public function removeProductRow($index)
    {
        if (count($this->shipmentProducts) > 1) {
            unset($this->shipmentProducts[$index]);
            $this->shipmentProducts = array_values($this->shipmentProducts);
            $this->calculateTotals();
        }
    }
    public function updatedShipmentProducts($value, $key)
    {
        $parts = explode('.', $key);
        $index = (int) $parts[0];
        $field = $parts[1];

        // Validate index exists
        if (!isset($this->shipmentProducts[$index])) {
            return;
        }
        if ($this->shipmentProducts[$index]['product_id']) {
            $product = Product::where('id', $this->shipmentProducts[$index]['product_id'])->first();
        }

        if ($field === 'product_search') {
            $this->filterProducts($value, $index);
            return;
        }
        if ($this->shipmentProducts[$index]['product_id']) {
            if ($product && $product->has_expiry_date && $this->shipmentProducts[$index]['batch_id']) {
                $batch = BatchInventory::find($this->shipmentProducts[$index]['batch_id']);
                if (!$batch) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Selected batch is not available.');
                    Log::error('Selected batch is not available.');
                    return false;
                } elseif ($batch->quantity <= 0) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Batch qty is not available.');
                    Log::error('Batch qty is not available.');
                    return false;
                } elseif ($this->shipmentProducts[$index]['quantity'] > 0 && $batch->quantity < $this->shipmentProducts[$index]['quantity']) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Batch qty is not available.');
                    Log::error('Batch qty is not available.');
                } else {
                    $this->resetValidation('shipmentProducts.' . $index . '.quantity');
                }
            } elseif ($product && $this->shipmentProducts[$index]['quantity']) {
                $productInventory = StockCount::where('product_id', $product->id)
                    ->where('location_id', $this->location_id)->first();
                if (!$productInventory) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Stock not found!.');
                    Log::error('Stock not available.');
                    return false;
                } elseif ($this->shipmentProducts[$index]['quantity'] > 0 && $productInventory->on_hand_quantity < $this->shipmentProducts[$index]['quantity']) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Quantity not available. Available qty : ' . $productInventory->on_hand_quantity);
                    Log::error('Quantity not available.');
                } else {
                    $this->resetValidation('shipmentProducts.' . $index . '.quantity');
                }
            }
        }

    }
    public function filterProducts($search, $index)
    {
        if (empty($search)) {
            $this->filtered_products = collect();
            $this->show_dropdown_index = null;
        } else {
            $selectedProductIds = collect($this->shipmentProducts)
                ->pluck('product_id')
                ->filter()
                ->all();

            $this->show_dropdown_index = $index;
            $this->filtered_products = $this->products->filter(function ($product) use ($search, $selectedProductIds) {
                return !in_array($product->id, $selectedProductIds) && (
                    stripos($product->product_name, $search) !== false ||
                    stripos($product->product_code, $search) !== false
                );
            });
        }
    }
    public function getBatchesForProduct($productId)
    {
        if (!$productId)
            return [];

        try {
            return BatchInventory::where('product_id', $productId)
                ->where('quantity', '>', 0)
                ->where('location_id', $this->location_id)
                ->select('id', 'batch_number', 'quantity', 'expiry_date')
                ->orderBy('expiry_date', 'asc')
                ->get()
                ->map(function ($batch) {
                    return [
                        'id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'available_quantity' => $batch->quantity,
                        'expiry_date' => $batch->expiry_date,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching batches for product: ' . $e->getMessage());
            return [];
        }
    }
    public function calculateRowTotal($index)
    {
        if (!isset($this->shipmentProducts[$index])) {
            return;
        }

        $quantity = floatval($this->shipmentProducts[$index]['quantity'] ?? 0);
        logger($quantity);

        // Fetch product unit conversion data
        $unitData = ProductUnit::where('product_id', $this->shipmentProducts[$index]['product_id'])
            ->where('unit_id', $this->shipmentProducts[$index]['shipment_unit_id'])
            ->select('operator', 'conversion_factor')
            ->first();
        // Get the price value, not the model
        $product = Product::where('id', $this->shipmentProducts[$index]['product_id'])
            ->select('price')
            ->first();
        $basePrice = $product ? floatval($product->price) : 0;
        if ($unitData) {
            if ($unitData->operator == 'multiply') {
                $unitPrice = $basePrice * $unitData->conversion_factor;
            } elseif ($unitData->operator == 'divide') {
                $unitPrice = $unitData->conversion_factor != 0
                    ? $basePrice / $unitData->conversion_factor
                    : 0;
            } else {
                $unitPrice = $basePrice;
            }
        } else {
            $unitPrice = $basePrice;
        }

        $this->shipmentProducts[$index]['net_unit_price'] = round($unitPrice, 2);
        $this->shipmentProducts[$index]['total_price'] = round($quantity * $unitPrice, 2);

        $this->calculateTotals();
    }
    public function calculateTotals()
    {
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($this->shipmentProducts as $product) {
            $totalQuantity += floatval($product['quantity'] ?? 0);
            $totalPrice += floatval($product['total_price'] ?? 0);
        }

        $this->total_quantity = $totalQuantity;
        $this->total_price = round($totalPrice, 2);
        $this->grand_total = round($totalPrice, 2); // Add taxes, discounts, etc. here if needed
    }
    private function validateShipmentProducts(): bool
    {
        $hasError = false;

        logger('inside validation');

        foreach ($this->shipmentProducts as $index => $productData) {
            logger($productData);
            $product = Product::find($productData['product_id'] ?? null);
            if (!$product) {
                $this->addError("shipmentProducts.{$index}.product_id", 'Invalid product selected.');
                $hasError = true;
                continue;
            }

            $quantity = $productData['quantity'] ?? 0;
            if ($quantity == 0) {
                $this->addError("shipmentProducts.{$index}.quantity", 'Invalid Quantity.');
                $hasError = true;
                continue;
            }

            if ($product->has_expiry_date) {
                $batch = BatchInventory::find($productData['batch_id']);
                if (!$batch || $batch->quantity <= 0) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Batch not available.');
                    $hasError = true;
                } elseif ($quantity > 0 && $batch->quantity < $quantity) {
                    $this->addError("shipmentProducts.{$index}.quantity", "Insufficient batch qty. Available: {$batch->quantity}");
                    $hasError = true;
                } else {
                    $this->resetValidation("shipmentProducts.{$index}.quantity");
                }
            } elseif (!$product->has_expiry_date) {
                $stock = StockCount::where('product_id', $product->id)
                    ->where('location_id', $this->location_id)
                    ->first();

                if (!$stock) {
                    $this->addError("shipmentProducts.{$index}.quantity", 'Stock not found.');
                    $hasError = true;
                } elseif ($stock->on_hand_quantity < $quantity) {
                    $this->addError("shipmentProducts.{$index}.quantity", "Qty not available. Available: {$stock->on_hand_quantity}");
                    $hasError = true;
                } else {
                    $this->resetValidation("shipmentProducts.{$index}.quantity");
                }
            }
        }

        return !$hasError;
    }

    public function confirmShipment()
    {
        $this->dispatch('open-modal', 'shipping_confirmation_modal');
    }
    public function closeConfirmModal()
    {
        $this->dispatch('close-modal', 'shipping_confirmation_modal');
    }
    public function fetchShipment()
    {
        $this->dispatch('close-modal', 'shipping_confirmation_modal');
        return $this->redirect('/fedex-shipping?shipment_id=' . $this->shipmentId);
    }

    public function save()
    {
        $this->isSaving = true;

        try {
            logger('Entered save method');

            if (!$this->validateShipmentProducts()) {
                logger("Validation failed in shipment products");
                return;
            }

            logger('Validation passed');

            DB::transaction(function () {
                $shipmentData = [
                    'shipment_number' => $this->shipment_number,
                    'user_id' => Auth::id(),
                    'customer_id' => $this->customer_id,
                    'location_id' => $this->location_id,
                    'total_quantity' => $this->total_quantity,
                    'total_price' => $this->total_price,
                    'grand_total' => $this->grand_total,
                    'status' => 'Shipping',
                ];

                if ($this->editMode && $this->shipmentId) {
                    $shipment = Shipment::findOrFail($this->shipmentId);
                    $shipment->update($shipmentData);

                    // Restore stock from existing shipment products
                    foreach ($shipment->shipmentProducts()->get() as $productData) {
                        $batch = null;

                        if ($productData->batch_id) {
                            $batch = BatchInventory::where('id', $productData->batch_id)
                                ->where('location_id', $shipment->location_id)
                                ->first();
                        }

                        logger('Restocking: ', ['batch' => $batch]);

                        if ($batch) {
                            $batch->increment('quantity', $productData->quantity);
                        } else {
                            $stock = StockCount::where('product_id', $productData->product_id)
                                ->where('location_id', $shipment->location_id)
                                ->first();

                            if ($stock) {
                                $stock->increment('on_hand_quantity', $productData->quantity);
                            }
                        }
                    }

                    // Delete old shipment products
                    $shipment->shipmentProducts()->delete();
                } else {
                    $shipment = Shipment::create($shipmentData);
                }

                $this->total_quantity = 0;
                // Add new shipment products and update inventory
                foreach ($this->shipmentProducts as $productData) {
                    $this->total_quantity += $productData['quantity'];
                    ShipmentProducts::create([
                        'shipment_id' => $shipment->id,
                        'product_id' => $productData['product_id'],
                        'batch_id' => $productData['batch_id'],
                        'quantity' => $productData['quantity'],
                        'shipment_unit_id' => $productData['shipment_unit_id'],
                        'net_unit_price' => $productData['net_unit_price'],
                        'total_price' => $productData['total_price'],
                    ]);

                    $batch = null;
                    if (!empty($productData['batch_id'])) {
                        $batch = BatchInventory::where('id', $productData['batch_id'])
                            ->where('location_id', $shipment->location_id)
                            ->first();
                    }

                    if ($batch) {
                        $batch->decrement('quantity', $productData['quantity']);
                    } else {
                        $stock = StockCount::where('product_id', $productData['product_id'])
                            ->where('location_id', $shipment->location_id)
                            ->first();

                        if ($stock) {
                            $stock->decrement('on_hand_quantity', $productData['quantity']);
                        }
                    }
                }
                $shipment->total_quantity = $this->total_quantity;
                $shipment->save();
            });

            $this->closeModal();
            $this->dispatch('shipment-saved');
            $this->dispatch('show-notification', 'Shipment created successfully!', 'success');
        } catch (ValidationException $e) {
            throw $e; // Let Livewire handle validation errors
        } catch (\Exception $e) {
            Log::error('Error saving shipment: ' . $e->getMessage());
            $this->dispatch('show-notification', 'An error occurred while saving the shipment. Please try again.', 'error');
        } finally {
            $this->isSaving = false;
        }
    }


    public function render()
    {
        return view('livewire.organization.shipping.shipping-component');
    }
}