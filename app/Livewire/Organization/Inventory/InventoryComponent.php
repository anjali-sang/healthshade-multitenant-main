<?php

namespace App\Livewire\Organization\Inventory;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Mycatalog;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockCount;
use App\Models\Unit;
use Livewire\Attributes\On;

use Livewire\Component;

class InventoryComponent extends Component
{
    

    public $product_name = '';
    public $product_id = '';
    public $organization_id = '';
    public $location_id = '';
    public $location_name = '';
    public $product_cost = 0;
    public $added_by = '';
    public $unit_id = '';
    public $total = 0;

    public $quantity = 1;
    public $units = [];

    public $locations = [];
    public $selectedLocation = null;

    protected $queryString = ['selectedLocation'];

    public function incrementQuantity()
    {

        $this->quantity++;
        $this->updateFinalPrice();

    }
    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->updateFinalPrice();
        }
    }

    public function updatedSelectedLocation()
    {
        $this->dispatch('inventoryIocationChanged', $this->selectedLocation);
    }

    public function mount()
    {
        $this->locations = Location::where('org_id', auth()->user()->organization_id)->where('is_active', true)->get();
        $this->selectedLocation = auth()->user()->location_id ?? null;
    }
    public function updateFinalPrice()
    {
        if (!$this->unit_id || !$this->quantity) {
            $this->total = 0;
            return;
        }

        $productUnit = ProductUnit::where('product_id', $this->product_id)
            ->where('unit_id', $this->unit_id)
            ->first();

        if (!$productUnit) {
            $this->total = 0;
            return;
        }

        $conversionFactor = $productUnit->conversion_factor ?? 1;

        // Simplified conversion logic
        $basePrice = $productUnit->operator === 'multiply'
            ? $this->product_cost / $conversionFactor
            : $this->product_cost * $conversionFactor;

        $this->total = $basePrice * $this->quantity;
    }

    #[On('cartIconClick')]
    public function cartIconClick($rowId)
    {
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('add_to_cart') && $user->role_id > 2) {
            $this->dispatch('show-notification','You don\'t have permission to Add/Remove Products from cart!', 'error');
            return;
        }
        $inv = StockCount::where('id', $rowId)->first();

        $existingCartItem = Cart::where('product_id', $inv->product_id)
            ->where('organization_id', auth()->user()->organization_id)
            ->where('location_id', $inv->location_id)
            ->first();

        if ($existingCartItem) {
            $auditService = app(\App\Services\InventoryAuditService::class);
            $event = 'Removed';
            $message = 'Following Product is removed from cart';
            $auditService->logCartChanges(
                $existingCartItem?->id,
                $event,
                $message
            );
            $existingCartItem->delete();
            $this->dispatch('cartUpdated')->to('CartIcon');
            $this->dispatch('pg:eventRefresh-inventory-list-aftzfa-table');
            return;
        }

        $this->location_name = $inv->location->name;
        $location = $inv->location_id;

        if ($location == null) {
            if (auth()->user()->role_id == '2') {
                $this->dispatch('show-notification','Assign a location to yourself in the Users Section.', 'error');
            }
            if (auth()->user()->role_id == '3') {
                $this->dispatch('show-notification', 'Contact your Admin to assign a location.', 'error');
            }
            return;
        }

        $this->product_id = $inv->product_id;
        $this->location_id = $location;
        $product = StockCount::find(id: $rowId);

        if (!$product) {
           $this->dispatch('show-notification','Product not found.', 'error');
            return;
        }
        $catalogData = Mycatalog::where('product_id', $this->product_id)->where('organization_id', auth()->user()->organization_id)->first();

        $this->product_name = $product->product->product_name;
        $this->product_cost = $product->product->cost;
        $this->added_by = auth()->user()->id;

        $this->units = ProductUnit::with('unit')
            ->where('product_id', $this->product_id)
            ->get()
            ->map(function ($productUnit) {
                return [
                    'unit_id' => $productUnit->unit_id,
                    'unit_name' => $productUnit->unit->unit_name,
                    'is_base_unit' => $productUnit->is_base_unit,
                    'operator' => $productUnit->operator,
                    'conversion_factor' => $productUnit->conversion_factor,
                ];
            });
        $baseUnit = $this->units->firstWhere('is_base_unit', true);

        if ($baseUnit) {
            $this->unit_id = $baseUnit['unit_id'];
            $this->quantity = 1;
            $this->updateFinalPrice();
        }

        $this->dispatch('open-modal', 'add-product-to-cart');
    }
    public function addProductToCart()
    {
        // Ensure all required data is available
        if (!$this->product_id || !$this->unit_id || !$this->quantity || !$this->total || !$this->location_id) {
            $this->dispatch('show-notification','Missing required information to add the product to the cart.', 'error');
            return;
        }
        $cart = Cart::create([
            'product_id' => $this->product_id,
            'organization_id' => auth()->user()->organization_id,
            'location_id' => $this->location_id,
            'added_by' => auth()->user()->id,
            'quantity' => $this->quantity,
            'price' => $this->total,
            'unit_id' => $this->unit_id,
        ]);
        $event = 'Added';
        $message = 'Following Product is added to cart';
        $auditService = app(\App\Services\InventoryAuditService::class);
        $auditService->logCartChanges(
            $cart->id,
            $event,
            $message
        );

        $this->dispatch('cartUpdated')->to('CartIcon');
        $this->dispatch('pg:eventRefresh-inventory-list-aftzfa-table');
        $this->dispatch('close-modal', 'add-product-to-cart');
        $this->dispatch('show-notification','Product added to the cart successfully.', 'success');
    }
    
    public function render()
    {
        return view('livewire.organization.inventory.inventory-component');
    }
}