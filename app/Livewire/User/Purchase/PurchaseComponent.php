<?php

namespace App\Livewire\User\Purchase;

use App\Livewire\User\Settings\CategoriesComponent;
use App\Models\BatchInventory;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\PurchaseOrder;
use App\Models\StockCount;
use App\Services\AckServices;
use App\Services\InvoiceServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;

class PurchaseComponent extends Component
{

    public $selectedTab = 'purchase';

    public $user = '';
    public $organization_id = null;
    public $viewPurchaseOrder = false;
    public $searchPurchaseOrder = null;
    public $purchaseOrderList = [];
    public $showModal = false;
    public $purchaseOrder = null;
    public $receivedQuantities = [];

    public $locations = [];

    public $biological_products = [];

    public $selectedLocation = [];

    public $previewUrl, $selectedPurchaseOrder;

    protected $rules = [
        'receivedQuantities.*' => 'numeric|min:0',
    ];
    protected $messages = [
        'receivedQuantities.*.numeric' => 'Received quantity must be a number',
        'receivedQuantities.*.min' => 'Received quantity cannot be negative',
    ];
    public $showBiologicalModal = false;
    public $generatedBarcodes = [];
    public $showBarcode = false;
    public $purchasedProductId, $batchInventory = [];
    public $batchDetails = [];

    public $chart_number = [];

    public $productLots = [];
    public $showMultipleLots = [];

    protected InvoiceServices $invoiceService;
    protected AckServices $ackServices;

    public function boot(InvoiceServices $invoiceService, AckServices $ackServices)
    {
        $this->invoiceService = $invoiceService;
        $this->ackServices = $ackServices;
    }

    public function mount()
    {
        $this->locations = Location::where('org_id', auth()->user()->organization_id)->where('is_active', true)->get();
        $this->selectedLocation = auth()->user()->location_id ?? null;
        $this->fetchPurchaseData();
    }

    public function fetchPurchaseData()
    {
        $this->user = auth()->user();
        $this->organization_id = $this->user->organization_id;

        $query = PurchaseOrder::with([
            'purchaseSupplier:id,supplier_name,supplier_email',
            'purchaseLocation:id,name'
        ])
            ->whereHas('purchaseSupplier')
            ->where('organization_id', $this->organization_id)
            ->where('purchase_orders.status', '!=', 'completed');

        if ($this->selectedLocation) {
            $query->where('location_id', $this->selectedLocation);
        }

        if ($this->searchPurchaseOrder) {
            $query->where(function ($q) {
                $q->where('purchase_oder_number', 'LIKE', "%{$this->searchPurchaseOrder}%")
                    ->orWhere('merge_id', 'LIKE', "%{$this->searchPurchaseOrder}%")
                    ->orWhereHas('purchaseSupplier', function ($supplierQuery) {
                        $supplierQuery->where('supplier_name', 'LIKE', "%{$this->searchPurchaseOrder}%");
                    });
            });
        }

        $this->purchaseOrderList = $query->get();
    }


    #[On('rowClicked')]
    public function fetchPoModal($id)
    {
        $this->fetchPurchaseData();
        $this->viewPurchaseOrder = true;
        $this->selectPo($id);
    }
    public function selectPo($id)
    {
        // Fetch purchase order separately to avoid conflicts with joins
        $this->purchaseOrder = PurchaseOrder::with(['purchasedProducts.product', 'purchasedProducts.unit', 'purchaseLocation'])
            ->where('purchase_orders.id', $id)
            ->leftJoin('bill_to_locations', function ($join) {
                $join->on('purchase_orders.bill_to_location_id', '=', 'bill_to_locations.location_id')
                    ->on('purchase_orders.supplier_id', '=', 'bill_to_locations.supplier_id');
            })
            ->select('purchase_orders.*', 'bill_to_locations.bill_to')
            ->first();

    }
    public function receiveProduct($id)
    {
        $this->reset([
            'biological_products',
            'generatedBarcodes',
            'batchDetails',
            'chart_number',
            'showBarcode',
            'batchInventory',
            'productLots',
            'showMultipleLots'
        ]);
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('receive_orders') && $user->role_id > 2) {
            $this->dispatch('show-notification', "You dont have permission to receive Purchase orders.", 'error');
            return;
        }

        $this->purchaseOrder = PurchaseOrder::with(['purchasedProducts.product', 'purchasedProducts.unit', 'purchaseLocation'])
            ->where('id', $id)
            ->first();

        if (!$this->purchaseOrder) {
            $this->dispatch('show-notification', "No Purchase Order selected.", 'error');
            return;
        }

        $this->initializeReceivedQuantities();
        $this->dispatch('open-modal', 'receive_product_model');
    }
    private function initializeReceivedQuantities()
    {
        foreach ($this->purchaseOrder->purchasedProducts as $product) {
            $this->receivedQuantities[$product->id] = $product->quantity - $product->received_quantity;
            if ($product->product->has_expiry_date) {
                $this->batchDetails[$product->product->id] = [
                    'batch_number' => '',
                    'expiry_date' => ''
                ];
            }
        }
    }

    public function addLot($productId)
    {
        $product = $this->purchaseOrder->purchasedProducts->find($productId);

        if (!$product)
            return;

        if (!isset($this->productLots[$productId])) {
            // Initialize with existing single lot data if available
            $existingBatch = $this->batchDetails[$product->product->id] ?? null;
            $this->productLots[$productId] = [];

            if ($existingBatch && ($existingBatch['batch_number'] || $existingBatch['expiry_date'])) {
                $this->productLots[$productId][] = [
                    'batch_number' => $existingBatch['batch_number'],
                    'expiry_date' => $existingBatch['expiry_date'],
                    'quantity' => $this->receivedQuantities[$productId] ?? 0
                ];
            } else {
                $this->productLots[$productId][] = [
                    'batch_number' => '',
                    'expiry_date' => '',
                    'quantity' => $this->receivedQuantities[$productId] ?? 0
                ];
            }
        }
        // Add new lot
        $this->productLots[$productId][] = [
            'batch_number' => '',
            'expiry_date' => '',
            'quantity' => 0
        ];

        // Clear the single batch details since we're now using multiple lots
        unset($this->batchDetails[$product->product->id]);
        $this->receivedQuantities[$productId] = 0; // Reset since we're now tracking via lots
    }

    // Remove lot for a product
    public function removeLot($productId, $lotIndex)
    {
        if (isset($this->productLots[$productId][$lotIndex])) {
            unset($this->productLots[$productId][$lotIndex]);
            // Re-index array
            $this->productLots[$productId] = array_values($this->productLots[$productId]);

            // If only one lot remains, convert back to single lot mode
            if (count($this->productLots[$productId]) <= 1) {
                $product = $this->purchaseOrder->purchasedProducts->find($productId);
                if ($product && count($this->productLots[$productId]) == 1) {
                    $remainingLot = $this->productLots[$productId][0];
                    $this->batchDetails[$product->product->id] = [
                        'batch_number' => $remainingLot['batch_number'],
                        'expiry_date' => $remainingLot['expiry_date']
                    ];
                    $this->receivedQuantities[$productId] = $remainingLot['quantity'];
                    unset($this->productLots[$productId]);
                }
            }
        }
    }

    private function initializeProductLots()
    {
        foreach ($this->purchaseOrder->purchasedProducts as $product) {
            // Check if product requires lot numbers (you can modify this condition based on your business logic)
            $requiresLot = $this->productRequiresLot($product->product);

            if ($requiresLot) {
                // Initialize with one lot entry
                $this->productLots[$product->id] = [
                    [
                        'batch_number' => '',
                        'expiry_date' => '',
                        'quantity' => $product->quantity - $product->received_quantity
                    ]
                ];
            }
        }
    }
    private function productRequiresLot($product)
    {
        // Add your logic here - this could be based on product category, type, or a specific field
        // Example: Check if product is biological or has expiry tracking enabled
        return $product->categories()->whereRaw('LOWER(category_name) = ?', ['biological'])->exists()
            || $product->has_expiry_date;
    }
    public function incrementQuantity($productId, $lotIndex = null)
    {
        if ($lotIndex !== null && isset($this->productLots[$productId][$lotIndex])) {
            $this->productLots[$productId][$lotIndex]['quantity']++;
        } else {
            $this->receivedQuantities[$productId]++;
        }
    }

    public function decrementQuantity($productId, $lotIndex = null)
    {
        if ($lotIndex !== null && isset($this->productLots[$productId][$lotIndex])) {
            if ($this->productLots[$productId][$lotIndex]['quantity'] > 0) {
                $this->productLots[$productId][$lotIndex]['quantity']--;
            } else {
                $this->dispatch('show-notification', "Lot quantity cannot be less than 0.", 'error');
            }
        } else {
            if ($this->receivedQuantities[$productId] > 0) {
                $this->receivedQuantities[$productId]--;
            } else {
                $this->dispatch('show-notification', "Receiving quantity cannot be less than 0.", 'error');
            }
        }
    }


    public function updateReceiveQuantity()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Validate lot quantities first
            if ($this->productLots) {
                foreach ($this->productLots as $productId => $lots) {
                    $purchasedProduct = $this->purchaseOrder->purchasedProducts->find($productId);
                    if (!$purchasedProduct)
                        continue;

                    $totalLotQuantity = array_sum(array_column($lots, 'quantity'));
                    $maxAllowed = $purchasedProduct->quantity - $purchasedProduct->received_quantity;
                    logger('  Validate lot quantities first ');
                    logger($purchasedProduct);
                    if ($totalLotQuantity > $maxAllowed) {
                        $this->addError("productLots.{$productId}", 'Total quantities cannot exceed ordered quantity');
                        DB::rollBack();
                        return;
                    }

                    // Validate that lots have required information
                    foreach ($lots as $index => $lot) {
                        if ($lot['quantity'] > 0 && empty($lot['batch_number'])) {
                            $this->addError("productLots.{$productId}.{$index}.batch_number", 'Batch number is required');
                            DB::rollBack();
                            return;
                        }
                        if ($lot['quantity'] > 0 && empty($lot['expiry_date'])) {
                            $this->addError("productLots.{$productId}.{$index}.expiry_date", 'Date is required');
                            DB::rollBack();
                            return;
                        }
                    }
                }
            } else {
                foreach ($this->receivedQuantities as $productId => $receivedQty) {
                    if ($receivedQty > 0) {
                        $purchasedProduct = $this->purchaseOrder->purchasedProducts->find($productId);
                        $expiryDateRaw = $this->batchDetails[$purchasedProduct->product_id]['expiry_date'] ?? null;
                        $batchNumber = $this->batchDetails[$purchasedProduct->product_id]['batch_number'] ?? null;
                        logger('  else Validate lot quantities first ');
                        logger($purchasedProduct);

                        if ($purchasedProduct->product->has_expiry_date != '0') {
                            if (empty($batchNumber) || trim($batchNumber) === '' || empty($expiryDateRaw)) {
                                $this->addError("receivedQuantities.{$productId}", 'Batch/Lot number and expiry date is required for all products');
                                DB::rollBack();
                                return;
                            }
                        }
                    }
                }
            }




            // Process regular quantities (non-lot products or single lot products)
            foreach ($this->receivedQuantities as $productId => $receivedQty) {
                // Skip if this product has multiple lots
                if (isset($this->productLots[$productId]))
                    continue;

                $purchasedProduct = $this->purchaseOrder->purchasedProducts->find($productId);
                if (!$purchasedProduct || $receivedQty <= 0)
                    continue;

                $previouslyReceivedQuantity = $purchasedProduct->received_quantity;
                if ($receivedQty > ($purchasedProduct->quantity - $purchasedProduct->received_quantity)) {
                    $this->addError("receivedQuantities.{$productId}", 'Received quantity cannot exceed ordered quantity');
                    DB::rollBack();
                    return;
                }

                logger(' Process regular quantities (non-lot products or single lot products ');
                logger($purchasedProduct);
                $purchasedProduct->update([
                    'received_quantity' => $receivedQty + $purchasedProduct->received_quantity,
                ]);

                // Audit log
                $auditService = app(\App\Services\PurchaseOrderAuditService::class);
                $auditService->logProductReceiving(
                    $this->purchaseOrder,
                    $purchasedProduct->product_id,
                    $previouslyReceivedQuantity,
                    $purchasedProduct->received_quantity
                );

                // Update stock
                $this->updateProductStock($purchasedProduct->product_id, $purchasedProduct->unit_id, $receivedQty, $this->purchaseOrder->location_id);

                // Handle single batch details
                $batchNumber = $this->batchDetails[$purchasedProduct->product_id]['batch_number'] ?? null;
                $expiryDateRaw = $this->batchDetails[$purchasedProduct->product_id]['expiry_date'] ?? null;
                if ($expiryDateRaw && preg_match('/^\d{4}-\d{2}$/', $expiryDateRaw)) {
                    $expiryDate = $expiryDateRaw . '-01';
                } else {
                    $expiryDate = $expiryDateRaw;
                }
                if ($batchNumber) {
                    $existingBatch = BatchInventory::where('product_id', $purchasedProduct->product_id)
                        ->where('batch_number', $batchNumber)
                        ->where('expiry_date', $expiryDate)
                        ->where('location_id', $this->purchaseOrder->location_id)
                        ->where('organization_id', auth()->user()->organization_id)
                        ->first();

                    if ($existingBatch) {
                        $existingBatch->quantity += $receivedQty;
                        $existingBatch->save();
                    } else {
                        BatchInventory::create([
                            'product_id' => $purchasedProduct->product_id,
                            'batch_number' => $batchNumber,
                            'expiry_date' => $expiryDate,
                            'quantity' => $receivedQty,
                            'organization_id' => auth()->user()->organization_id,
                            'location_id' => $this->purchaseOrder->location_id,
                        ]);
                    }
                }

                // Check if biological product
                $biological = Product::where('id', $purchasedProduct->product_id)
                    ->whereHas('categories', function ($query) {
                        $query->whereRaw('LOWER(category_name) = ?', ['biological']);
                    })
                    ->first();

                if ($biological) {
                    $this->biological_products[] = [
                        'product_id' => $biological->id,
                        'product_code' => $biological->product_code,
                        'product_name' => $biological->product_name,
                    ];
                }
            }

            // Process lot-based quantities
            foreach ($this->productLots as $productId => $lots) {
                $purchasedProduct = $this->purchaseOrder->purchasedProducts->find($productId);
                if (!$purchasedProduct)
                    continue;

                logger('  Process lot-based quantities ');
                logger($purchasedProduct);

                $totalReceived = 0;
                $previouslyReceivedQuantity = $purchasedProduct->received_quantity;

                foreach ($lots as $lot) {
                    if ($lot['quantity'] <= 0)
                        continue;

                    $totalReceived += $lot['quantity'];

                    $expiryDateRaw = $lot['expiry_date'] ?? null;
                    if ($expiryDateRaw && preg_match('/^\d{4}-\d{2}$/', $expiryDateRaw)) {
                        $expiryDate = $expiryDateRaw . '-01';
                    } else {
                        $expiryDate = $expiryDateRaw;
                    }

                    // Create or update batch inventory
                    $existingBatch = BatchInventory::where('product_id', $purchasedProduct->product_id)
                        ->where('batch_number', $lot['batch_number'])
                        ->where('expiry_date', $expiryDate)

                        ->where('location_id', $this->purchaseOrder->location_id)
                        ->where('organization_id', auth()->user()->organization_id)
                        ->first();

                    if ($existingBatch) {
                        $existingBatch->quantity += $lot['quantity'];
                        $existingBatch->save();
                    } else {
                        BatchInventory::create([
                            'product_id' => $purchasedProduct->product_id,
                            'batch_number' => $lot['batch_number'],
                            'expiry_date' => $expiryDate,
                            'quantity' => $lot['quantity'],
                            'organization_id' => auth()->user()->organization_id,
                            'location_id' => $this->purchaseOrder->location_id,
                        ]);
                    }
                }

                if ($totalReceived > 0) {
                    // Update purchased product received quantity
                    $purchasedProduct->update([
                        'received_quantity' => $totalReceived + $purchasedProduct->received_quantity,
                    ]);

                    // Audit log for lot-based products
                    $auditService = app(\App\Services\PurchaseOrderAuditService::class);
                    $auditService->logProductReceiving(
                        $this->purchaseOrder,
                        $purchasedProduct->product_id,
                        $previouslyReceivedQuantity,
                        $purchasedProduct->received_quantity
                    );

                    // Update stock for lot-based products
                    $this->updateProductStock($purchasedProduct->product_id, $purchasedProduct->unit_id, $totalReceived, $this->purchaseOrder->location_id);

                    // Check if biological product
                    $biological = Product::where('id', $purchasedProduct->product_id)
                        ->whereHas('categories', function ($query) {
                            $query->whereRaw('LOWER(category_name) = ?', ['biological']);
                        })
                        ->first();

                    if ($biological) {
                        $this->biological_products[] = [
                            'product_id' => $biological->id,
                            'product_code' => $biological->product_code,
                            'product_name' => $biological->product_name,
                        ];
                    }
                }
            }

            // Check if purchase order is fully received
            $allProductsReceived = true;
            foreach ($this->purchaseOrder->purchasedProducts as $product) {
                if ($product->received_quantity < $product->quantity) {
                    $allProductsReceived = false;
                    break;
                }
            }

            // Update purchase order status if all products are fully received
            if ($allProductsReceived && $this->purchaseOrder->status !== 'completed') {
                $this->purchaseOrder->update([
                    'status' => 'completed',
                    'received_date' => now()
                ]);
            } elseif (!$allProductsReceived && $this->purchaseOrder->status === 'ordered') {
                $this->purchaseOrder->update([
                    'status' => 'partial'
                ]);
            }

            DB::commit();

            // Reset form data
            $this->receivedQuantities = [];
            $this->productLots = [];
            $this->batchDetails = [];
            $this->showModal = false;

            // Refresh purchase order data
            $this->purchaseOrder->load('purchasedProducts.product', 'purchasedProducts.unit');

            // Show success message
            $this->dispatch('show-notification', 'Products received successfully!', 'success');
            $this->fetchPurchaseData();
            // Handle biological products notification if any
            // if (!empty($this->biological_products)) {
            //     $biologicalNames = collect($this->biological_products)->pluck('product_name')->join(', ');
            //     $this->dispatch('show-notification', "Biological products received: {$biologicalNames}. Please ensure proper storage conditions.", 'error');
            // }

            $this->dispatch('close-modal', 'receive_product_model');

        } catch (\Throwable $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Product receiving failed: ' . $e->getMessage(), [
                'purchase_order_id' => $this->purchaseOrder->id,
                'user_id' => auth()->id(),
                'error' => $e->getTraceAsString()
            ]);

            $this->dispatch('show-notification', 'Failed to receive products. Please try again.', 'error');
        }
    }
    private function updateProductStock($productId, $unitId, $quantity, $location)
    {
        try {
            logger('inside updateProduct stock function');
            logger('product id ' . $productId . ' unit id is ' . $unitId . ' qty is ' . $quantity . ' locaion is ' . $location);
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

    // Helper method to handle unit conversions
    private function convertQuantityToBaseUnit($productId, $unitId, $quantity)
    {
        // Get the product's base unit and conversion
        $productUnit = ProductUnit::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->first();

        if (!$productUnit) {
            throw new \Exception('Unit conversion not found for product ' . $productId . 'and unit is ' . $unitId);
        }
        if ($productUnit->is_base_unit) {
            return $quantity;
        }
        return $quantity * $productUnit->conversion_factor;
    }

    private function updatePurchaseOrderStatus()
    {
        $allCompleted = $this->purchaseOrder->purchasedProducts
            ->every(function ($product) {
                return $product->received_quantity >= $product->quantity;
            });

        $anyReceived = $this->purchaseOrder->purchasedProducts
            ->some(function ($product) {
                return $product->received_quantity > 0;
            });

        $status = $allCompleted ? 'completed' : ($anyReceived ? 'partial' : 'pending');

        $this->purchaseOrder->update(['status' => $status]);
    }

    private function resetForm()
    {
        $this->receivedQuantities = [];
        $this->showModal = false;
    }

    // Invoice and ack preview related functions starts
    public function previewInvoice($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->invoiceService->getPreviewUrl($purchaseOrder);

        if ($result['success']) {
            $this->selectedPurchaseOrder = $result['purchase_order'];
            $this->previewUrl = $result['url'];
            $this->dispatch('open-modal', 'preview_modal');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function previewAck($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->ackServices->getPreviewUrl($purchaseOrder);
        if ($result['success']) {
            $this->selectedPurchaseOrder = $result['purchase_order'];
            $this->previewUrl = $result['url'];
            $this->dispatch('open-modal', 'preview_modal');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function closePreview()
    {
        $this->dispatch('close-modal', 'preview_modal');
    }
    // Invoice and ack preview related functions ends
    // Search PO filter functions starts
    public function clearSearch()
    {
        $this->searchPurchaseOrder = '';
        $this->fetchPurchaseData();
    }
    public function updatedsearchPurchaseOrder()
    {
        $this->fetchPurchaseData();
    }
    public function updatedSelectedLocation()
    {
        $user = auth()->user();
        $role = $user->role;
        if ($role?->hasPermission('all_purchase') || $user->role_id <= 2) {
            $this->fetchPurchaseData();
        }
    }
    // Search PO filter functions ends


    public function render()
    {
        return view('livewire.user.purchase.purchase-component');
    }

    //print biological code , not required for now

    // public function cancelBarcodeModal()
    // {
    //     $this->showBiologicalModal = false;
    //     $this->reset(['biological_products', 'generatedBarcodes', 'showBarcode']);
    //     $this->dispatch('close-modal', 'biological_product_modal');
    // }

    // public function printBiologicalBarcodes()
    // {
    //     if (empty($this->biological_products)) {
    //          $this->dispatch('show-notification','No biological products to generate barcodes for.', 'error');
    //         return;
    //     }

    //     $generator = new BarcodeGeneratorSVG();

    //     // Initialize chart_number property if it doesn't exist
    //     if (!isset($this->chart_number)) {
    //         $this->chart_number = [];
    //     }

    //     foreach ($this->biological_products as $product) {
    //         // Generate product barcode
    //         $this->generatedBarcodes[$product['product_id']] = $generator->getBarcode(
    //             $product['product_code'],
    //                 $generator::TYPE_CODE_128
    //         );

    //         // Generate chart number barcode
    //         $productId = $product['product_id'];
    //         $chartNumber = $this->chart_number[$productId] ?? '';

    //         if (!empty($chartNumber)) {
    //             $chartBarcodeKey = $chartNumber . $productId;
    //             $this->generatedBarcodes[$chartBarcodeKey] = $generator->getBarcode(
    //                 $chartNumber,
    //                     $generator::TYPE_CODE_128
    //             );
    //         }
    //     }

    //     $this->showBarcode = true;
    //     $this->dispatch('printBarcodes');
    // }
}