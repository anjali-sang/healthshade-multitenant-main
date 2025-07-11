<?php

namespace App\Livewire\User\Inventory;

use App\Models\InventoryTransfer;
use App\Models\Location;
use App\Models\StockCount;
use DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class InventoryTransferComponent extends Component
{
    public $inventoryTransfer = '';
    public $user;
    public $organization_id;
    public $notifications = [];
    public $selectedProduct;
    public $selectedLocation;
    public $locations = [];
    public $to_location_id;
    public $total = '0';

    public $transferQty = 1;

    public function cancelTransfer()
    {
        $this->reset(['selectedProduct', 'transferQty','inventoryTransfer']);
        $this->dispatch('close-modal', 'transfer_product_modal');
    }

    #[On('transferProduct')]
    public function transferProduct($rowId)
    {
        $this->reset();

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

        $this->selectedLocation = $this->selectedProduct->location;
        $this->locations = Location::where('org_id', auth()->user()->organization_id)->get();
        $this->inventoryTransfer = InventoryTransfer::generateTransferNumber();
        $this->dispatch('open-modal', 'transfer_product_modal');
    }

    public function updateTransfer()
    {
        if (!$this->selectedProduct) {
            $this->addNotification('No product selected', 'error');
            return;
        }

        // Convert values to integers/floats to avoid string subtraction
        $transferQty = (float)$this->transferQty;
        $onHandQty = (float)$this->selectedProduct->on_hand_quantity;

        // Validate that transferment won't make inventory negative
        if ($transferQty > $onHandQty) {
            $this->addNotification('Enough Qty is not available to trnasfer', 'error');
            return;
        }

        try {
            DB::beginTransaction();

            $transfer = InventoryTransfer::create([
                'reference_number' => $this->inventoryTransfer,
                'product_id' => $this->selectedProduct->product_id,
                'quantity' => $transferQty,
                'from_location_id' => $this->selectedLocation->id,
                'to_location_id' => $this->to_location_id,
                'unit_id' => $this->selectedProduct->product->units[0]->unit->id,
                'supplier_id' => $this->selectedProduct->product->product_supplier_id,
                'organization_id' => auth()->user()->organization_id,
                'user_id' => auth()->user()->id,
            ]);

        // Reduce quantity from 'from_location'
        StockCount::where('product_id', $this->selectedProduct->product_id)
            ->where('location_id', $this->selectedLocation->id)
            ->decrement('on_hand_quantity', $transferQty);

        // Increase quantity in 'to_location'
        StockCount::updateOrCreate(
            [
                'product_id' => $this->selectedProduct->product_id,
                'location_id' => $this->to_location_id,
            ],
            [
                'on_hand_quantity' => DB::raw("on_hand_quantity + {$transferQty}")
            ]
        );

            DB::commit();

            $this->addNotification('Product transfered successfully!', 'success');
            $this->reset(['selectedProduct', 'transferQty']); // Changed from pickQuantity to adjustQty
            $this->dispatch('pg:eventRefresh-inventory-transfer-list-aagmwq-table');
            $this->dispatch('close-modal', 'transfer_product_modal');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->addNotification('Error: ' . $e->getMessage(), 'error');
        }
    }


    public function addNotification($message, $type = 'success')
    {
        $this->notifications[] = [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ];
    }

    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }


    public function render()
    {
        return view('livewire.user.inventory.inventory-transfer-component');
    }
}
