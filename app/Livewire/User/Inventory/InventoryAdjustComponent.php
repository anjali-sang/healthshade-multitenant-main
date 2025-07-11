<?php

namespace App\Livewire\User\Inventory;

use App\Models\InventoryAdjust;
use App\Models\StockCount;
use DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class InventoryAdjustComponent extends Component
{

    public $inventoryAdjust = '';
    public $user;
    public $organization_id;
    public $notifications = [];
    public $selectedProduct;
    public $selectedLocation;
    public $locations = [];
    public $selected_location_id;
    public $total = '0';

    public $adjustQty = 1;
    public $adjustType = 'add';

    public function cancelAdjustment()
    {
        $this->reset(['selectedProduct', 'adjustQty']);
        $this->dispatch('close-modal', 'adjust_product_modal');

    }

    #[On('adjustProduct')]
    public function adjustProduct($rowId)
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

        $this->inventoryAdjust = InventoryAdjust::generateAdjustNumber();
        $this->dispatch('open-modal', 'adjust_product_modal');
    }

    public function updateAdjustment()
    {
        if (!$this->selectedProduct) {
            $this->addNotification('No product selected', 'error');
            return;
        }
        $adjustQty = (float) $this->adjustQty;
        $onHandQty = (float) $this->selectedProduct->on_hand_quantity;

        if($this->adjustType == 'subtract') {
            if($this->adjustQty > $this->selectedProduct->on_hand_quantity){
                $this->addNotification('Please enter a valid quantity', 'error');
                return;
            }
            $newOnHandQty =  $onHandQty-$adjustQty; 
        }else{
            $newOnHandQty =  $onHandQty+$adjustQty; ; 
        }

        try {
            DB::beginTransaction();

            $adjustment = InventoryAdjust::create([
                'reference_number' => $this->inventoryAdjust,
                'product_id' => $this->selectedProduct->product_id,
                'quantity' => $adjustQty, // Use the converted value
                'unit_id' => $this->selectedProduct->product->units[0]->unit->unit_name,
                'supplier_id' => $this->selectedProduct->product->product_supplier_id,
                'organization_id' => auth()->user()->organization_id,
                'user_id' => auth()->user()->id,
                'location_id' => $this->selectedProduct->location_id,
                'previous_quantity' => $onHandQty,
                'new_quantity' =>$newOnHandQty,
            ]);
            $this->selectedProduct->on_hand_quantity = $newOnHandQty; 
            $this->selectedProduct->save();

            DB::commit();

            $this->addNotification('Product adjusted successfully!', 'success');
            $this->reset(['selectedProduct', 'adjustQty']);
            $this->dispatch('pg:eventRefresh-inventory-adjust-list-4akuef-table');
            $this->dispatch('close-modal', 'adjust_product_modal');

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
        return view('livewire.user.inventory.inventory-adjust-component');
    }
}
