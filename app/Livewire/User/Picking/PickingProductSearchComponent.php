<?php

namespace App\Livewire\User\Picking;

use App\Models\StockCount;
use Livewire\Component;

class PickingProductSearchComponent extends Component
{
    public $user;
    public $organization_id;
    public $products;
    public $search = '';
    public $selected_location_id;

    protected $queryString = ['search'];
    protected $listeners = ['locationSelected'];

    public function mount()
    {
        $this->user = auth()->user();
        $this->organization_id = $this->user->organization_id;
        $this->selected_location_id = $this->user->location_id;        
        if ($this->selected_location_id) {
            $this->products = $this->fetchProducts();
        }
    }

    public function dispatchProductSelection($productId)
    {
        $this->dispatch('productSelected', productId: $productId);
    }

    public function locationSelected($locationId)
    {
        $this->selected_location_id = $locationId;
        $this->search = '';
        $this->products = $this->fetchProducts();
    }

    public function updatedSearch()
    {
        $this->products = $this->fetchProducts();
    }

    public function fetchProducts()
    {

        $query = StockCount::query()
            ->where('organization_id', $this->organization_id)
            ->where('on_hand_quantity', '>', 0);

        // Only add location filter if location_id is set
        if ($this->selected_location_id) {
            $query->where('location_id', $this->selected_location_id);
        }

        $products = $query->whereHas('product', function ($query) {
                $query->where(function ($q) {
                    $q->where('product_name', 'like', '%' . $this->search . '%')
                    ->orWhere('product_code', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['product:id,product_name,product_code'])
            ->leftJoin('products', 'products.id', '=', 'stock_counts.product_id')
            ->select('stock_counts.*')
            ->orderBy('products.product_name', 'asc')
            ->get();
        
        return $products;
    }

    public function render()
    {
        return view(
            'livewire.user.picking.picking-product-search-component',
            [
                'products' => $this->products,
            ]
        );
    }
}