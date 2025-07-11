<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;

class CartIcon extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('all_location_cart') || $user->role_id <= 2 ) {
            $existingCartItem = Cart::where('carts.organization_id', $user->organization_id)
                ->join('products', 'products.id', '=', 'carts.product_id')
                ->join('locations', 'locations.id', '=', 'carts.location_id')
                ->where('locations.is_active', true)
                ->where('products.is_active', true)
                ->where('products.organization_id', auth()->user()->organization_id)
                ->count();
        }else {
            $existingCartItem = Cart::where('carts.organization_id', $user->organization_id)
                ->join('products', 'products.id', '=', 'carts.product_id')
                ->where('products.is_active', true)
                ->where('location_id', $user->location_id)
                ->where('products.organization_id', auth()->user()->organization_id)
                ->count();
        }

        $this->cartCount = $existingCartItem;
    }

    public function goToCart()
    {
        return redirect()->route('cart.index');
    }


    public function render()
    {
        return view('livewire.cart-icon');
    }
}