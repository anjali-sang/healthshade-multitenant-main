<?php

namespace App\Livewire\Organization\Barcode;

use App\Models\Product;
use Livewire\Component;
use Picqer\Barcode\BarcodeGeneratorSVG;

class PrintBarcodeComponent extends Component
{
    public $search = '';
    public $products = [];
    public $selectedProducts = [];
    public $barcodes = [];
    public $showModal = false;

    public $searchTerm = null;
    public $searchResults = [];

    public $searchQuery = '';

    public $selectedProductId = null;
    public $quantity = 1;
    public $showResults = false;

    public $notifications = [];
public $barcodeSize = 'medium'; // small, medium, large
public $barcodeLayout = 'grid'; 

public function updatedBarcodeLayout($value){
    $this->barcodeLayout = $value;
}

    public function showModal()
    {
        $this->showModal = true;
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

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->searchResults = [];
    }

    public function selectProduct($id)
    {
        $this->selectedProductId = $id;
        $this->searchTerm = '';
        $this->searchResults = [];

        $product = Product::find($this->selectedProductId);
        foreach ($this->selectedProducts as $key => $selectedProduct) {
            if ($selectedProduct['id'] == $product->id) {
                // Update quantity instead of adding new
                $this->selectedProducts[$key]['quantity'] += $this->quantity;
                return;
            }
        }
        $this->selectedProducts[] = [
            'id' => $product->id,
            'product_code' => $product->product_code,
            'product_name' => $product->product_name,
            'quantity' => $this->quantity,
        ];
        $this->addNotification('Product added successfully', 'success');
    }
    public function incrementQuantity($productId)
    {
        foreach ($this->selectedProducts as $key => $product) {
            if ($product['id'] == $productId) {
                $this->selectedProducts[$key]['quantity']++;
                break;
            }
        }
    }
    public function clearAllProducts()
    {
        $this->selectedProducts = [];
        $this->updateSession();
        $this->dispatchBrowserEvent('products-cleared', ['message' => 'All products have been cleared']);
    }

    public function decrementQuantity($productId)
    {
        foreach ($this->selectedProducts as $key => $product) {
            if ($product['id'] == $productId && $product['quantity'] > 1) {
                $this->selectedProducts[$key]['quantity']--;
                break;
            }
        }
    }

    public function removeProduct($productId)
    {
        $this->selectedProducts = array_values(array_filter(
            $this->selectedProducts,
            function ($product) use ($productId) {
                return $product['id'] != $productId;
            }
        ));
        $this->addNotification('Product removed from selection', 'success');
    }

    public function generateBarcode()
    {
        $generator = new BarcodeGeneratorSVG();
        $this->barcodes = []; // Clear previous barcodes

        foreach ($this->selectedProducts as $product) {
            $barcode = $generator->getBarcode($product['product_code'], $generator::TYPE_CODE_128);
            $this->barcodes[] = [
                'product_name' => $product['product_name'],
                'barcode' => $barcode,
                'product_code' => $product['product_code'],
                'quantity' => $product['quantity'],
            ];
        }

        // Emit Livewire event to open modal
        $this->dispatch('open-modal', 'print-barcode-modal');

    }


    public function downloadBarcodes()
{
    // Create a view with barcodes
    $html = view('pdf.barcodes', [
        'barcodes' => $this->barcodes,
        'barcodeSize' => $this->barcodeSize
    ])->render();
    
    // Return as a downloadable file
    return response()->streamDownload(function() use ($html) {
        echo $html;
    }, 'product-barcodes.html');
}


    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ]);

        // Limit to a maximum of 3-5 notifications if needed
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        }));
    }

    public function render()
    {
        return view('livewire.organization.barcode.print-barcode-component');
    }
}
