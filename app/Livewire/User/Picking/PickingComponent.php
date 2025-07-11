<?php

namespace App\Livewire\User\Picking;

use App\Models\BatchInventory;
use App\Models\BatchPicking;
use App\Models\Location;
use App\Models\Patient;
use App\Models\PickingDetailsModel;
use App\Models\PickingModel;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockCount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Once;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Picqer\Barcode\BarcodeGeneratorSVG;

class PickingComponent extends Component
{
    use WithPagination;

    public $isPicking = true;
    public $pickingNumber = '';
    public $user;
    public $organization_id;
    public $selectedProduct;
    public $selectedLocation;
    public $selected_location_id;
    public $total = '0';
    public $pickQuantity = 1;
    public $showBiologicalModal = false;
    public $chart_number = null;
    public $biological_product;

    public $generatedBarcodes;

    public function mount()
    {
        $this->selectedLocation = auth()->user()->location_id ?? null;
    }

    function switchTab($type)
    {
        if ($type == 'picking') {
            $this->isPicking = true;
        } else {
            $this->isPicking = false;
        }
        $this->dispatch('pickingLocationChanged', $this->selectedLocation);
    }

    public function updatedSelectedLocation()
    {
        $this->dispatch('pickingLocationChanged', $this->selectedLocation);
    }
    public function cancelPicking()
    {
        $this->isPicking = false;
        $this->dispatch('close-modal', 'picking_product_modal');
        $this->dispatch('close-modal', 'picking_batch_modal');
        $this->reset(['selectedProduct', 'pickQuantity']);
    }
    public function incrementQuantity()
    {
        logger('pick qty => '.$this->pickQuantity);
        logger(' selected product => '.$this->selectedProduct);
        logger(' avialble qty => '.$this->selectedProduct->on_hand_quantity);

        // Check if we haven't reached the maximum allowed quantity
        if ($this->pickQuantity < $this->selectedProduct->on_hand_quantity) {
            $this->pickQuantity++;
        }
    }

    public function decrementQuantity()
    {
        // Ensure we don't go below zero
        if ($this->pickQuantity > 0) {
            $this->pickQuantity--;
        }
    }

    public function updatePicking()
    {

        if (!$this->selectedProduct) {
            $this->dispatch('show-notification', 'No product selected!', 'error');
            return;
        }

        if ($this->pickQuantity <= 0) {
            $this->dispatch('show-notification', 'Please enter a valid quantity', 'error');
            return;
        }

        try {
            DB::beginTransaction();

            $picking = PickingModel::create([
                'picking_number' => $this->pickingNumber,
                'organization_id' => auth()->user()->organization_id,
                'location_id' => $this->selectedProduct->location_id,
                'user_id' => auth()->user()->id,
                'total' => $this->selectedProduct->product->product_price * $this->pickQuantity,
            ]);
            PickingDetailsModel::create([
                'picking_id' => $picking->id,
                'product_id' => $this->selectedProduct->product_id,
                'picking_quantity' => $this->pickQuantity,
                'picking_unit' => $this->selectedProduct->product->units[0]->unit->unit_name,
                'net_unit_price' => $this->selectedProduct->product->cost,
                'sub_total' => $this->selectedProduct->product->cost * $this->pickQuantity,
            ]);

            $auditService = app(\App\Services\PickingAuditService::class);
            $auditService->logPickingCreation(
                $picking,
                $this->selectedProduct->product_id,
                $this->pickQuantity,
                $this->selectedProduct->product->units[0]->unit->unit_name
            );

            $this->selectedProduct->on_hand_quantity -= $this->pickQuantity;
            $this->selectedProduct->save();

            DB::commit();
            $this->dispatch('close-modal', 'picking_product_modal');
            $this->dispatch('show-notification', 'Product picked successfully!', 'success');
            $this->reset(['selectedProduct', 'pickQuantity']);
            $this->dispatch('pg:eventRefresh-picking-inventory-list-q7yfsl-table');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->dispatch('show-notification', 'Error: ' . $e->getMessage(), 'error');
        }
    }

    #[On('pickProduct')]
    public function pickProduct($rowId)
    {
        $this->reset(['chart_number', 'biological_product']);
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('pick_products') && $user->role_id > 2) {
            $this->dispatch('show-notification', 'You don\'t have permission to Pick products!', 'error');
            return;
        }

        // Fetch selected product with relationships
        $this->selectedProduct = StockCount::where('id', $rowId)
            ->with([
                'product',
                'location',
                'product.units' => function ($query) {
                    $query->where('is_base_unit', true)->with('unit');
                }
            ])
            ->first();

        $this->pickingNumber = PickingModel::generatePickingNumber();
        $this->dispatch('open-modal', 'picking_product_modal');
    }
    #[On('pickBatchProduct')]
    public function pickBatchProduct($rowId)
    {
        $this->reset(['chart_number', 'biological_product']);
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('pick_products') && $user->role_id > 2) {
            $this->dispatch('show-notification', 'You don\'t have permission to Pick products!', 'error');
            return;
        }

        // Fetch selected product with relationships
        $this->selectedProduct = BatchInventory::where('id', $rowId)
            ->with([
                'product',
                'location',
                'product.units' => function ($query) {
                    $query->where('is_base_unit', true)->with('unit');
                }
            ])
            ->first();

        $this->pickingNumber = BatchPicking::generatePickingNumber();
        $this->dispatch('open-modal', 'picking_batch_modal');
    }

    public function updateBatchPicking()
    {

        if (!$this->selectedProduct) {
            $this->dispatch('show-notification', 'No product selected!', 'error');
            return;
        }

        if ($this->pickQuantity <= 0) {
            $this->dispatch('show-notification', 'Please enter a valid quantity', 'error');
            return;
        }

        try {
            DB::beginTransaction();

            $picking = BatchPicking::create([
                'picking_number' => $this->pickingNumber,
                'location_id' => $this->selectedProduct->location_id,
                'batch_id' => $this->selectedProduct->batch_number,
                'organization_id' => auth()->user()->organization_id,
                'user_id' => auth()->user()->id,
                'total' => $this->selectedProduct->product->product_price * $this->pickQuantity,
                'product_id' => $this->selectedProduct->product_id,
                'picking_quantity' => $this->pickQuantity,
                'picking_unit' => $this->selectedProduct->product->units[0]->unit->unit_name,
                'net_unit_price' => $this->selectedProduct->product->cost,
                'total_amount' => $this->selectedProduct->product->cost * $this->pickQuantity,
                'chart_number' => $this->chart_number,
            ]);

            // Create the patient
            Patient::create([
                'chartnumber' => $this->chart_number,
                'organization_id' => auth()->user()->organization_id,
                'drug' => $this->selectedProduct->product->product_name,
                'date_given' => now(),
            ]);

            $auditService = app(\App\Services\BatchPickingAuditService::class);
            $auditService->logPickingCreation(
                $picking,
                $this->selectedProduct->product_id,
                $this->pickQuantity,
                $this->selectedProduct->product->units[0]->unit->unit_name
            );
            $this->selectedProduct->quantity -= $this->pickQuantity;
            $this->selectedProduct->save();

            DB::commit();
            $this->dispatch('close-modal', 'picking_batch_modal');
            $this->dispatch('show-notification', 'Product picked successfully!', 'success');
            $this->reset(['selectedProduct', 'pickQuantity']);
            $this->dispatch('pg:eventRefresh-batch-picking-list-ga40i5-table');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->dispatch('show-notification', 'Error: ' . $e->getMessage(), 'error');
        }
    }

    public function cancelBarcodeModal()
    {
        $this->showBiologicalModal = false;
        $this->reset(['biological_products', 'generatedBarcodes', 'showBarcode']);
        $this->dispatch('close-modal', 'biological_product_modal');
    }

    public function printBiologicalBarcodes()
    {
        if (empty($this->biological_product)) {
            $this->dispatch('show-notification', 'No biological products to generate barcodes for.', 'warning');
            return;
        }

        $generator = new BarcodeGeneratorSVG();
        // Generate chart number barcode
        $chartNumber = $this->chart_number ?? '';

        if (!empty($chartNumber)) {
            $this->generatedBarcodes = $generator->getBarcode(
                $chartNumber,
                    $generator::TYPE_CODE_128
            );
        }

        $this->dispatch('printChartNumberBarcodes');
        $this->dispatch('redirect-to-patient');
    }
    public function render()
    {
        $locations = Location::where('org_id', auth()->user()->organization_id)->where('is_active', true)->get();
        return view('livewire.user.picking.picking-component', compact('locations'));
    }
}