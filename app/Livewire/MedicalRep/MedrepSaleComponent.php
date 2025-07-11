<!-- <?php

// namespace App\Livewire\MedicalRep;

// use App\Models\MedicalRepSales;
// use App\Models\MedicalRepSalesProducts;
// use App\Models\MedrepShipment;
// use App\Models\Organization;
// use App\Models\PurchaseOrder;
// use Livewire\Component;
// use App\Models\ProductUnit;
// use App\Models\Shipment;
// use App\Models\ShipmentProducts;
// use App\Models\Location;
// use App\Models\Product;
// use App\Models\StockCount;
// use App\Models\Unit;
// use Illuminate\Log\Logger;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Validation\ValidationException;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx\FunctionPrefix;
// use App\Services\UPSShippingService;

// class MedrepSaleComponent extends Component
// {
//     public $editMode = false;
//     public $shipmentId = null;

//     // sale properties
//     public $sale_number = '';
//     public $organization_id = '';
//     public $location_id;
//     public $total_quantity = 0;
//     public $total_price = 0;
//     public $grand_total = 0;

//     // sale products
//     public $saleProducts = [];

//     // Available options
//     public $organizations = [];
//     public $locations = [];
//     public $products = [];
//     public $units = [];

//     // Loading states
//     public $isLoading = false;
//     public $isSaving = false;
//     public $products_search = '';
//     public $selected_product_name = '';
//     public $show_dropdown = false;

//     public $filtered_products = [];

//     public $show_dropdown_index = null;

//     public $viewMode = false;
//     public $saleId = null;

//     public $prepareSale = false;
//     public $sale;
//     public $shipment;
//     public $showShippingModal = false;
//     public $showTrackingModal = false;
//     public $trackingInfo = [];
//     public $errorMessage = '';
//     public $successMessage = '';

//     protected $listeners = [
//         'close-modal' => 'closeModal',
//         'show-shipping-modal' => 'showShippingModal'
//     ];

//     protected $rules = [
//         'organization_id' => 'required|exists:organizations,id',
//         'location_id' => 'required|exists:locations,id',
//         'saleProducts' => 'required|array|min:1',
//         'saleProducts.*.product_id' => 'required|exists:products,id',
//         'saleProducts.*.quantity' => 'required|numeric|min:1',
//         'saleProducts.*.sale_unit_id' => 'required|exists:units,id',
//         'saleProducts.*.net_unit_price' => 'required|numeric|min:0',
//     ]; -->

//     // protected $messages = [
//     //     'organization_id.required' => 'Please select a organization.',
//     //     'organization_id.exists' => 'Selected organization is invalid.',
//     //     'location_id.required' => 'Please select a location.',
//     //     'location_id.exists' => 'Selected location is invalid.',
//     //     'saleProducts.required' => 'Please add at least one product.',
//     //     'saleProducts.min' => 'Please add at least one product.',
//     //     'saleProducts.*.product_id.required' => 'Please select a product.',
//     //     'saleProducts.*.product_id.exists' => 'Selected product is invalid.',
//     //     'saleProducts.*.quantity.required' => 'Please enter quantity.',
//     //     'saleProducts.*.quantity.min' => 'Quantity must be at least 1.',
//     //     'saleProducts.*.quantity.numeric' => 'Quantity must be a valid number.',
//     //     'saleProducts.*.sale_unit_id.required' => 'Please select a unit.',
//     //     'saleProducts.*.sale_unit_id.exists' => 'Selected unit is invalid.',
//     //     'saleProducts.*.net_unit_price.required' => 'Please enter unit price.',
//     //     'saleProducts.*.net_unit_price.numeric' => 'Unit price must be a valid number.',
//     //     'saleProducts.*.net_unit_price.min' => 'Unit price cannot be negative.',
//     // ];
//     // public function updatedProductsSearch()
//     // {
//     //     if (empty($this->products_search)) {
//     //         $this->filtered_products = $this->products;
//     //         $this->product_id = '';
//     //         $this->selected_brand_name = '';
//     //         $this->show_dropdown = false;
//     //     } else {
//     //         $this->show_dropdown = true;
//     //         $this->filtered_products = $this->products->filter(function ($product) {
//     //             return stripos($product->product_name, $this->products_search) !== false;
//     //         });
//     //     }

//     // }

//     // public function updated($name, $value)
//     // {
//     //     if ($name === 'organization_id') {
//     //         logger('Livewire v3: updated fired for organization_id');
//     //         $this->locations = Location::where('org_id', $value)
//     //             ->where('is_active', true)
//     //             ->select('id', 'name')
//     //             ->orderBy('name')
//     //             ->get();
//     //     }
//     // }
//     // public function showDropdown($index)
//     // {
//     //     $this->show_dropdown_index = $index;
//     //     $this->filtered_products = $this->products;
//     // }

//     // public function hideDropdown()
//     // {
//     //     $this->dispatch('hide-dropdown-delayed');
//     // }
//     // public function mount($saleId = null)
//     // {
//     //     $this->loadOptions();
//     //     $this->addProductRow();
//     //     if ($saleId) {
//     //         $this->saleId = $saleId;
//     //         $this->loadLatestSale();
//     //         $this->loadShipment();
//     //     }
//     // }
//     // public function loadLatestSale()
//     // {
//     //     $this->sale = MedicalRepSales::with([
//     //         'organization',
//     //         'receiverOrganization',
//     //         'location',
//     //         'saleItems.product'
//     //     ])->find($this->saleId);
//     // }

//     // public function loadShipment()
//     // {
//     //     $this->shipment = MedrepShipment::where('sale_id', $this->saleId)->first();
//     // }

//     // public function showShippingModal($saleId)
//     // {
//     //     $this->saleId = $saleId;
//     //     $this->loadLatestSale();
//     //     $this->loadShipment();
//     //     $this->showShippingModal = true;
//     //     $this->resetMessages();
//     // }

//     // public function closeModal($modalName = null)
//     // {
//     //     if ($modalName === 'shipping_confirmation_modal' || !$modalName) {
//     //         $this->showShippingModal = false;
//     //     }


//     //     $this->dispatch('close-modal', 'medical-sale-modal');
//     //     $this->resetMessages();
//     // }

//     // public function fetchSale()
//     // {
//     //     $this->isLoading = true;
//     //     $this->resetMessages();

//     //     $sales = MedicalRepSales::find($this->saleId);
//     //     if ($sales && $sales->status === 'pending') {
//     //         $sales->status = 'completed';
//     //         $sales->save();
//     //         $saleItems = $sales->saleItems()->with('product')->get();
//     //         $firstProduct = $saleItems[0]->product;
//     //         $purchaseOrder = PurchaseOrder::create([
//     //             'purchase_oder_number' => PurchaseOrder::generatePurchaseOrderNumber(),
//     //             'merge_id' => null,
//     //             'supplier_id' => $firstProduct->product_supplier_id,
//     //             'organization_id' => $sales->receiver_org_id,  // receiver is placing the PO
//     //             'location_id' => $sales->location_id,
//     //             'bill_to_location_id' => $sales->location_id,
//     //             'ship_to_location_id' => $sales->location_id,
//     //             'status' => 'pending',
//     //             'total' => $sales->total_price,
//     //             'created_by' => auth()->id(),
//     //             'is_order_placed' => true,
//     //             'note' => 'Order generated through medical rep',
//     //         ]);

//     //         // ✅ Create Purchase Order details for each item
//     //         foreach ($sales->saleItems as $item) {
//     //              $originalProduct = $item->product;
//     //             // Clone product to receiver org
//     //             $newProduct = Product::create([
//     //                 'product_name' => $originalProduct->product_name,
//     //                 'product_code' => $originalProduct->product_code,
//     //                 'product_supplier_id' => $originalProduct->product_supplier_id,
//     //                 'product_description' => $originalProduct->product_description,
//     //                 'has_expiry_date' => $originalProduct->has_expiry_date,
//     //                 'manufacture_code' => $originalProduct->manufacture_code,
//     //                 'organization_id' => $sales->receiver_org_id, // New org
//     //                 'category_id' => $originalProduct->category_id,
//     //                 'cost' => $originalProduct->cost,
//     //                 'price' => $originalProduct->price,
//     //                 'is_active' => true,
//     //                 'brand_id' => $originalProduct->brand_id,
//     //                 'weight' => $originalProduct->weight,
//     //                 'length' => $originalProduct->length,
//     //                 'width' => $originalProduct->width,
//     //                 'height' => $originalProduct->height,
//     //                 'created_by' => auth()->id(),
//     //             ]);

//     //             // purchase order detail using new product ID
//     //             \App\Models\PurchaseOrderDetail::create([
//     //                 'purchase_order_id' => $purchaseOrder->id,
//     //                 'product_id' => $newProduct->id, //new product ID
//     //                 'quantity' => $item->quantity,
    //                 'sub_total' => $item->total,
    //                 'received_quantity' => 0,
    //                 'unit_id' => $item->unit_id,
    //             ]);
    //         }

    //         $this->dispatch('show-notification', 'Sale marked as completed.', 'success');
    //         $this->dispatch('close-modal', 'medical-sale-modal');
    //         $this->dispatch('close-modal', 'shipping_confirmation_modal');
    //         return;
    //     }

    //     try {
    //         logger('enter fetc sale');
    //         if ($this->shipment) {
    //             $this->errorMessage = 'Shipment already exists for this sale.';
    //             $this->isLoading = false;
    //             return;
    //         }

    //         logger('no sale previous sale exists');

    //         // Create UPS shipment
    //         $result = app(UPSShippingService::class)->createShipment($this->saleId);

    //         logger('UPS servies called');
    //         logger('UPS servies response');
    //         logger($result);



    //         if ($result['success']) {
    //             $this->successMessage = 'Shipment created successfully! Tracking Number: ' . $result['tracking_number'];

    //             // Update sale status
    //             $this->sale->update(['status' => 'shipped']);

    //             // Reload shipment data
    //             $this->loadShipment();

    //             // Close modal after 2 seconds
    //             $this->dispatch('close-modal', 'shipping_confirmation_modal');

    //             // Refresh parent component
    //             $this->dispatch('shipment-created');

    //         } else {
    //             $this->errorMessage = $result['message'];
    //         }

    //     } catch (\Exception $e) {
    //         Log::error('Shipment creation failed in Livewire', [
    //             'sale_id' => $this->saleId,
    //             'error' => $e->getMessage()
    //         ]);

    //         $this->errorMessage = 'An error occurred while creating the shipment. Please try again.';
    //     }

    //     $this->isLoading = false;
    // }

    // public function trackShipment()
    // {
    //     if (!$this->shipment || !$this->shipment->tracking_number) {
    //         $this->errorMessage = 'No tracking number available.';
    //         return;
    //     }

    //     $this->isLoading = true;
    //     $this->resetMessages();

    //     try {
    //         $result = app(UPSShippingService::class)->trackShipment($this->shipment->tracking_number);

    //         if ($result['success']) {
    //             $this->trackingInfo = $result['tracking_info'];
    //             $this->showTrackingModal = true;
    //         } else {
    //             $this->errorMessage = $result['message'];
    //         }

    //     } catch (\Exception $e) {
    //         $this->errorMessage = 'Error tracking shipment. Please try again.';
    //     }

    //     $this->isLoading = false;
    // }

    // public function downloadLabel()
    // {
    //     if (!$this->shipment || !$this->shipment->label_url) {
    //         $this->errorMessage = 'No shipping label available.';
    //         return;
    //     }

    //     return redirect()->route('shipping.label', $this->shipment->id);
    // }

    // private function resetMessages()
    // {
    //     $this->errorMessage = '';
    //     $this->successMessage = '';
    // }

    // public function selectProduct($index, $productId, $productName)
    // {
    //     $this->saleProducts[$index]['product_id'] = $productId;
    //     $this->saleProducts[$index]['product_search'] = $productName;
    //     $this->saleProducts[$index]['total_price'] = 0;

    //     $unitData = $this->getUnitsForProduct($productId)->first();
    //     $this->saleProducts[$index]['sale_unit_id'] = $unitData['id'] ?? null;
    //     $this->saleProducts[$index]['sale_unit'] = $unitData['unit'] ?? null;
    //     $this->calculateRowTotal($index);

    //     $this->show_dropdown_index = null;
    //     $this->filtered_products = collect();
    // }
    // public function getUnitsForProduct($productId)
    // {
    //     if (!$productId)
    //         return [];

    //     try {
    //         return ProductUnit::where('product_id', $productId)
    //             ->where('is_base_unit', true)
    //             ->with('unit')
    //             ->get()
    //             ->map(function ($product) {
    //                 return [
    //                     'id' => optional($product->unit)->id,
    //                     'unit' => optional($product->unit)->unit_name,
    //                     'code' => optional($product->unit)->unit_code,
    //                 ];
    //             });
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching units for product: ' . $e->getMessage());
    //         return [];
    //     }
    // }
    // public function loadOptions()
    // {
    //     try {
    //         $medrepId = Auth::id();
    //         $this->organizations = Organization::select('id', 'name', 'email')
    //             ->where('is_active', true)
    //             ->whereIn('id', function ($query) use ($medrepId) {
    //                 $query->select('org_id')
    //                     ->from('medrep_org_accesses')
    //                     ->where('medrep_id', $medrepId)
    //                     ->where('is_approved', true);
    //             })
    //             ->orderBy('name')
    //             ->get();
    //     } catch (\Exception $e) {
    //         Log::error('Error loading options: ' . $e->getMessage());
    //         session()->flash('error', 'Error loading form options. Please refresh the page.');
    //     }
    // }
    // public function updatedLocationId()
    // {
    //     $this->reset('saleProducts');
    //     $this->products = Product::select('id', 'product_name', 'product_code')
    //         ->where('is_active', true)
    //         ->where('organization_id', auth()->user()->organization_id)
    //         ->orderBy('product_name')
    //         ->get();
    // }

    // #[\Livewire\Attributes\On('view')]
    // public function viewShippingModal($saleId)
    // {
    //     $this->resetForm();

    //     if ($saleId) {
    //         $this->sale = MedicalRepSales::with(['saleItems.product', 'saleItems.unit'])
    //             ->find($saleId);
    //         $this->viewMode = true;
    //         $this->saleId = $saleId;
    //         $this->loadOptions();
    //         $this->loadSale($saleId);
    //     }
    //     $this->dispatch('open-modal', 'medical-sale-modal');
    // }
    // public function openSaleModal($saleId = null)
    // {
    //     if ($saleId) {
    //         $sales = MedicalRepSales::find($saleId);
    //         if ($sales) {
    //             logger('Sale not found with ID: ' . $sales->status);
    //             $sales->status == 'Completed';
    //             $this->dispatch('show-notification', 'Sale not found or already completed.', 'error');
    //             return;
    //         }
    //         $this->viewMode = false;
    //         $this->editMode = true;
    //         $this->saleId = $saleId;
    //         $this->loadOptions();
    //         $this->loadSale($saleId);
    //     } else {
    //         $this->resetForm();
    //         $this->editMode = false;
    //         $this->viewMode = false;
    //         $this->generateSaleNumber();
    //         $this->dispatch('open-modal', 'medical-sale-modal');
    //     }
    // }

    // public function resetForm()
    // {
    //     $this->sale_number = '';
    //     $this->organization_id = '';
    //     $this->location_id = '';
    //     $this->total_quantity = 0;
    //     $this->total_price = 0;
    //     $this->grand_total = 0;
    //     $this->saleProducts = [];
    //     $this->editMode = false;
    //     $this->saleId = null;
    //     $this->isLoading = false;
    //     $this->isSaving = false;
    //     $this->resetValidation();
    //     $this->addProductRow();
    // }

    // public function loadSale($saleId)
    // {
    //     try {
    //         $this->isLoading = true;

    //         logger('Loading sale with ID: ' . $saleId);

    //         $sale = MedicalRepSales::with(['saleItems.product', 'saleItems.unit'])
    //             ->findOrFail($saleId);
    //         $this->sale_number = $sale->sales_number;
    //         $this->organization_id = $sale->receiver_org_id;
    //         $this->location_id = $sale->location_id;
    //         $this->total_quantity = $sale->total_qty;
    //         $this->total_price = $sale->total_price;
    //         $this->grand_total = $sale->total_price;

    //         if ($this->organization_id) {
    //             $this->locations = Location::where('org_id', $this->organization_id)
    //                 ->where('is_active', true)
    //                 ->select('id', 'name')
    //                 ->orderBy('name')
    //                 ->get();
    //         } else {
    //             $this->locations = collect();
    //         }

    //         $this->products = Product::select('products.id', 'products.product_name', 'products.product_code')
    //             ->where('products.is_active', true)
    //             ->where('organization_id', operator: auth()->user()->organization_id)
    //             ->orderBy('products.product_name')
    //             ->get();


    //         $this->saleProducts = collect($sale->saleItems)->map(function ($product) {
    //             return [
    //                 'id' => $product->id,
    //                 'product_search' => $product->product->product_name ?? '',
    //                 'product_id' => $product->product_id,
    //                 'quantity' => $product->quantity,
    //                 'sale_unit_id' => $product->sale_unit_id ?? null,
    //                 'sale_unit' => $product->unit->unit_name ?? '', // may be missing
    //                 'net_unit_price' => $product->net_unit_price ?? $product->price ?? 0,
    //                 'total_price' => $product->total_price ?? $product->total ?? 0,
    //             ];
    //         })->toArray();

    //     } catch (\Exception $e) {
    //         Log::error('Error loading Sales: ' . $e->getMessage() . $e->getLine());
    //         session()->flash('error', 'Error loading sale data.');
    //         $this->closeModal();
    //     } finally {
    //         $this->isLoading = false;
    //     }
    // }

    // public function generateSaleNumber()
    // {
    //     try {
    //         $lastSale = MedicalRepSales::latest('id')->first();
    //         $nextNumber = $lastSale ? $lastSale->id + 1 : 1;
    //         $this->sale_number = 'SALE-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    //     } catch (\Exception $e) {
    //         Log::error('Error generating sale number: ' . $e->getMessage());
    //         $this->sale_number = 'SALE-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    //     }
    // }

    // public function addProductRow()
    // {
    //     $this->saleProducts[] = [
    //         'product_id' => '',
    //         'product_search' => '',
    //         'quantity' => 1,
    //         'sale_unit_id' => '',
    //         'net_unit_price' => 0,
    //         'total_price' => 0,
    //     ];
    // }
    // public function removeProductRow($index)
    // {
    //     if (count($this->saleProducts) > 1) {
    //         unset($this->saleProducts[$index]);
    //         $this->saleProducts = array_values($this->saleProducts);
    //         $this->calculateTotals();
    //     }
    // }
    // public function updatedSaleProducts($value, $key)
    // {
    //     $parts = explode('.', $key);
    //     $index = (int) $parts[0];
    //     $field = $parts[1];

    //     // Validate index exists
    //     if (!isset($this->saleProducts[$index])) {
    //         return;
    //     }
    //     if ($this->saleProducts[$index]['product_id']) {
    //         $product = Product::where('id', $this->saleProducts[$index]['product_id'])->first();
    //     }

    //     if ($field === 'product_search') {
    //         $this->filterProducts($value, $index);
    //         return;
    //     }

    // }
    // public function filterProducts($search, $index)
    // {
    //     if (empty($search)) {
    //         $this->filtered_products = collect();
    //         $this->show_dropdown_index = null;
    //     } else {
    //         $selectedProductIds = collect($this->saleProducts)
    //             ->pluck('product_id')
    //             ->filter()
    //             ->all();

    //         $this->show_dropdown_index = $index;
    //         $this->filtered_products = $this->products->filter(function ($product) use ($search, $selectedProductIds) {
    //             return !in_array($product->id, $selectedProductIds) && (
    //                 stripos($product->product_name, $search) !== false ||
    //                 stripos($product->product_code, $search) !== false
    //             );
    //         });
    //     }
    // }
    // public function calculateRowTotal($index)
    // {
    //     if (!isset($this->saleProducts[$index])) {
    //         return;
    //     }

    //     $quantity = floatval($this->saleProducts[$index]['quantity'] ?? 0);
    //     logger($quantity);

    //     // Fetch product unit conversion data
    //     $unitData = ProductUnit::where('product_id', $this->saleProducts[$index]['product_id'])
    //         ->where('unit_id', $this->saleProducts[$index]['sale_unit_id'])
    //         ->select('operator', 'conversion_factor')
    //         ->first();
    //     // Get the price value, not the model
    //     $product = Product::where('id', $this->saleProducts[$index]['product_id'])
    //         ->select('price')
    //         ->first();
    //     $basePrice = $product ? floatval($product->price) : 0;
    //     if ($unitData) {
    //         if ($unitData->operator == 'multiply') {
    //             $unitPrice = $basePrice * $unitData->conversion_factor;
    //         } elseif ($unitData->operator == 'divide') {
    //             $unitPrice = $unitData->conversion_factor != 0
    //                 ? $basePrice / $unitData->conversion_factor
    //                 : 0;
    //         } else {
    //             $unitPrice = $basePrice;
    //         }
    //     } else {
    //         $unitPrice = $basePrice;
    //     }

    //     $this->saleProducts[$index]['net_unit_price'] = round($unitPrice, 2);
    //     $this->saleProducts[$index]['total_price'] = round($quantity * $unitPrice, 2);

    //     $this->calculateTotals();
    // }
    // public function calculateTotals()
    // {
    //     $totalQuantity = 0;
    //     $totalPrice = 0;

    //     foreach ($this->saleProducts as $product) {
    //         $totalQuantity += floatval($product['quantity'] ?? 0);
    //         $totalPrice += floatval($product['total_price'] ?? 0);
    //     }

    //     $this->total_quantity = $totalQuantity;
    //     $this->total_price = round($totalPrice, 2);
    //     $this->grand_total = round($totalPrice, 2); // Add taxes, discounts, etc. here if needed
    // }
    // private function validateSaleProducts(): bool
    // {
    //     $hasError = false;

    //     logger('inside validation');

    //     foreach ($this->saleProducts as $index => $productData) {
    //         logger($productData);
    //         $product = Product::find($productData['product_id'] ?? null);
    //         if (!$product) {
    //             $this->addError("saleProducts.{$index}.product_id", 'Invalid product selected.');
    //             $hasError = true;
    //             continue;
    //         }

    //         $quantity = $productData['quantity'] ?? 0;
    //         if ($quantity == 0) {
    //             $this->addError("saleProducts.{$index}.quantity", 'Invalid Quantity.');
    //             $hasError = true;
    //             continue;
    //         }
    //     }

    //     return !$hasError;
    // }

    // public function confirmSale()
    // {
    //     $this->dispatch('open-modal', 'shipping_confirmation_modal');
    // }
    // public function closeConfirmModal()
    // {
    //     $this->dispatch('close-modal', 'shipping_confirmation_modal');
    // }

    // public function save()
    // {
    //     $this->isSaving = true;

    //     try {
    //         logger('Entered save method');

    //         if (!$this->validatesaleProducts()) {
    //             logger("Validation failed in sale products");
    //             return;
    //         }

    //         logger('Validation passed');

    //         DB::transaction(function () {
    //             $saleData = [
    //                 'sales_number' => $this->sale_number,
    //                 'medical_rep_id' => Auth::id(),
    //                 'org_id' => Auth::user()->organization_id,
    //                 'receiver_org_id' => $this->organization_id,
    //                 'location_id' => $this->location_id,
    //                 'total_qty' => $this->total_quantity,
    //                 'total_price' => $this->total_price,
    //                 'status' => 'pending',
    //                 'items' => count($this->saleProducts)
    //             ];

    //             if ($this->editMode && $this->saleId) {
    //                 $sale = MedicalRepSales::findOrFail($this->saleId);
    //                 $sale->update($saleData);
    //                 // Delete old sale products
    //                 $sale->items()->delete();
    //             } else {
    //                 $sale = MedicalRepSales::create($saleData);
    //             }

    //             $this->total_quantity = 0;
    //             // Add new sale products and update inventory
    //             foreach ($this->saleProducts as $productData) {
    //                 $this->total_quantity += $productData['quantity'];
    //                 MedicalRepSalesProducts::create([
    //                     'sales_id' => $sale->id,
    //                     'product_id' => $productData['product_id'],
    //                     'quantity' => $productData['quantity'],
    //                     'unit_id' => $productData['sale_unit_id'],
    //                     'price' => $productData['net_unit_price'],
    //                     'total' => $productData['total_price'],
    //                 ]);
    //             }
    //             $sale->total_qty = $this->total_quantity;
    //             $sale->save();
    //         });

    //         $this->closeModal();
    //         $this->dispatch('sale-saved');
    //         $this->dispatch('show-notification', 'sale created successfully!', 'success');
    //         $this->dispatch('pg:eventRefresh-sales-list-jklvkq-table');
    //     } catch (ValidationException $e) {
    //         throw $e; // Let Livewire handle validation errors
    //     } catch (\Exception $e) {
    //         Log::error('Error saving sale: ' . $e->getMessage());
    //         $this->dispatch('show-notification', 'An error occurred while saving the sale. Please try again.', 'error');
    //     } finally {
    //         $this->isSaving = false;
    //     }
    // }
//     public function render()
//     {
//         return view('livewire.medical-rep.medrep-sale-component');
//     }
// }
