<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\ProductSupplier;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;

class ProductDetailsComponent extends Component
{
    public $showModal = false;
    public $product = null;
    public $latestPurchaseOrders = [];
    public $productId = null;

    #[On('openProductDetailBrowser')]
    public function openProductDetailBrowser($id)
    {
        logger('Product ID received: ' . $id);
        
        $this->productId = $id;
        $this->loadProductDetails();
        $this->loadLatestPurchaseOrders();
        $this->showModal = true;
    }

    public function loadProductDetails()
    {
        $this->product = Product::with([
            'supplier',
            'categories', 
            'brand',
        ])->find($this->productId);
    }

    public function loadLatestPurchaseOrders()
{
    if (!$this->product) return;

    // Get latest 5 purchase orders that include this product
    $this->latestPurchaseOrders = PurchaseOrder::whereHas('purchasedProducts', function ($query) {
            $query->where('product_id', $this->productId);
        })
        ->orderBy('created_at', 'desc')
        ->with([
            'createdUser',
            'purchasedProducts' => function ($query) {
                $query->where('product_id', $this->productId)
                      ->with('unit'); // Include unit relation
            }
        ])
        ->limit(5)
        ->get()
        ->map(function ($po) {
            $poDetail = $po->purchasedProducts->first(); 

            return [
                'id' => $po->id,
                'po_number' => $po->po_number ?? 'PO-' . str_pad($po->id, 6, '0', STR_PAD_LEFT),
                'status' => $po->status,
                'total_amount' => $po->total_amount,
                'order_date' => $po->created_at->format('Y-m-d'),
                'created_by' => $po->createdUser->name ?? 'N/A',
                'ordered_quantity' => $poDetail?->quantity ?? 0,
                'ordered_unit' => $poDetail?->unit?->unit_name ?? 'N/A',
                'status_badge_class' => $this->getStatusBadgeClass($po->status)
            ];
        });
}


    private function getStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'approved' => 'bg-blue-100 text-blue-800 border-blue-200',
            'ordered' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'received' => 'bg-green-100 text-green-800 border-green-200',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
            'partial' => 'bg-orange-100 text-orange-800 border-orange-200',
            default => 'bg-green-100 text-green-800 border-green-200'
        };
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->product = null;
        $this->latestPurchaseOrders = [];
        $this->productId = null;
    }

    public function render()
    {
        return view('livewire.product-details-component');
    }
}