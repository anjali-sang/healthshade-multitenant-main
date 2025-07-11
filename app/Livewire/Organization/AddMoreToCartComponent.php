<?php

namespace App\Livewire\Organization;

use App\Models\Cart;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductUnit;
use Livewire\Component;

class AddMoreToCartComponent extends Component
{
    public $searchTerm = null;
    public $searchResults = [];
    public $product_name = '';
    public $location_name = '';
    public $product_cost = 0;
    public $units = [];
    public $total = 0;
    public $notifications = [];

    public $selectedLocation = null;
    public $selectedLocationName = null;
    public $unit_id = '';
    public $addToCartQty = 1;
    
    public $product_id = '';

    public function incrementQuantity()
    {

        $this->addToCartQty++;
        $this->updateFinalPrice();

    }
    public function decrementQuantity()
    {
        if ($this->addToCartQty > 1) {
            $this->addToCartQty--;
            $this->updateFinalPrice();
        }
    }
    public function mount($selectedLocation)
    {
        $this->selectedLocation = $selectedLocation;
        $this->selectedLocationName = Location::find($selectedLocation)->name ?? null;
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Product::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('product_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('product_description', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('product_code', 'like', '%' . $this->searchTerm . '%');
            })
            ->take(5)
            ->get();
    }

    public function addToCart($id)
    {
        $this->location_name = $this->selectedLocationName;
        $this->product_id = $id;
        if (!$this->selectedLocation) {
            $this->addNotification('Select a location  first to add into cart.', 'error');
            return;
        }
        $product = Product::find($id);
        if (!$product) {
            $this->addNotification('Product not found.', 'error');
            return;
        }
        $existingCartItem = Cart::where('product_id', $id)
            ->where('organization_id', auth()->user()->organization_id)
            ->where('location_id', $this->selectedLocation)
            ->first();

        if ($existingCartItem) {
            $this->addNotification('Product is already in cart for ' . $this->selectedLocationName . '.', 'error');
            return;
        }
        $this->product_name = $product->product_name;
        $this->product_cost = $product->cost;
        $this->added_by = auth()->user()->id;

        $this->units = ProductUnit::with('unit')
            ->where('product_id', $id)
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
            $this->addToCartQty = 1;
            $this->updateFinalPrice();
        }

        $this->dispatch('open-modal', 'add-product-to-cart');
    }

    public function updateFinalPrice()
    {
        if (!$this->unit_id || !$this->addToCartQty) {
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
        $basePrice = $productUnit->operator == 'multiply'
            ? $this->product_cost * $conversionFactor
            : $this->product_cost / $conversionFactor;
        $this->total = $basePrice * $this->addToCartQty;
    }
    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->searchResults = [];
    }

    public function addProductToCart()
    {

        if (!$this->product_id || !$this->unit_id || !$this->addToCartQty || !$this->total || !$this->selectedLocation) {
            $this->addNotification('Missing required information to add the product to the cart.', 'error');
            return;
        }

        if ($this->addToCartQty <= 0) {
            $this->addNotification('Quantity must be greater than zero.', 'error');
            return;
        }

        $cartItem = Cart::create([
            'product_id' => $this->product_id,
            'location_id' => $this->selectedLocation,
            'organization_id' => auth()->user()->organization_id,
            'quantity' => $this->addToCartQty,
            'unit_id' => $this->unit_id,
            'price' => $this->total,
            'added_by' => auth()->user()->id
        ]);

        $event = 'Added';
        $message = 'Following Product is added to cart';
        $auditService = app(\App\Services\InventoryAuditService::class);
        $auditService->logCartChanges(
            $cartItem->id,
            $event,
            $message
        );
        $this->dispatch('cartUpdated')->to('CartIcon');
        $this->dispatch('close-modal', 'add-product-to-cart');
        $this->addNotification('Product added to cart successfully.', 'success');
        $this->clearSearch();
        $this->dispatch('load-cart');
    }

    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type,
        ]);
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(
            array_filter($this->notifications, function ($notification) use ($id) {
                return $notification['id'] !== $id;
            }),
        );
    }
    public function render()
    {
        return view('livewire.organization.add-more-to-cart-component');
    }
}
