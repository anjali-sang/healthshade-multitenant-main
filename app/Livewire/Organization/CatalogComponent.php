<?php

namespace App\Livewire\Organization;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Mycatalog;
use App\Models\Product;
use App\Models\Location;
use App\Models\ProductUnit;
use App\Models\StockCount;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CatalogComponent extends Component
{
    public $locationData = [];
    public $notifications = [];
    public $categories = [];

    public $product_name = '';
    public $product_base_unit = '';

    public $product_cost = '';

    public $product_id = '';
    public $organization_id = '';
    public $location_id = '';
    public $added_by = '';
    public $unit_id = '';
    public $total = 0;

    public $locations = [];

    public $category_id;
    public $quantity = 1;
    public $units = [];
    public $mycatelog_id = null;

    public function mount($rowId = null)
    {
        $this->categories = Category::get();
        $product = Mycatalog::find($rowId);
        if ($product) {
            $this->category_id = $product->category_id;
        }

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

    #[On('edit')]
    public function editProduct($rowId)
    {

        $this->product_id = $rowId;
        $org_id = auth()->user()->organization_id;
        $myCatalog = Mycatalog::where('product_id', $rowId)
            ->where('organization_id', $org_id)
            ->first();

        $this->mycatelog_id = $myCatalog->id;
        $this->category_id = optional($myCatalog)->category_id;
        $this->category_name = optional(Category::find($this->category_id))->category_name ?? 'Unknown';
        $this->product_cost = $myCatalog->product_cost;

        $unit = ProductUnit::where('product_id', $rowId)
            ->where('is_base_unit', true)
            ->first();

        $this->product_base_unit = $unit ? $unit->unit->unit_name : null;

        $this->locations = Location::where('org_id', $org_id)->where('is_active',true)->get();

        // Fetch Stock Counts
        $stockCounts = StockCount::where('product_id', $rowId)
            ->where('organization_id', $org_id)
            ->join('locations', 'locations.id', '=', 'stock_counts.location_id')
            ->groupBy('product_id')
            ->groupBy('location_id')
            ->select('stock_counts.*', 'locations.name as location_name')
            ->get();

        // Initialize Location Data
        $this->locationData = [];
        foreach ($this->locations as $location) {
            $stockCount = $stockCounts->firstWhere('location_id', $location->id);

            $this->locationData[$location->id] = [
                'alert_quantity' => $stockCount ? $stockCount->alert_quantity : null,
                'par_quantity' => $stockCount ? $stockCount->par_quantity : null,
                'location_name' => $location->name
            ];
        }
        $this->dispatch('open-modal', 'edit-product-modal');

    }


    public function updateProduct()
    {
        $this->validate([
            'locationData.*.alert_quantity' => 'numeric',
            'locationData.*.par_quantity' => 'numeric',
            'product_cost' =>'required',
        ]);
        DB::transaction(function () {
            $org_id = auth()->user()->organization_id;
        
            foreach ($this->locationData as $location_id => $data) {
                StockCount::updateOrCreate(
                    [
                        'product_id' => $this->product_id,
                        'location_id' => $location_id,
                        'organization_id' => $org_id
                    ],
                    [
                        'alert_quantity' => $data['alert_quantity'] ?? 0,
                        'par_quantity' => $data['par_quantity'] ?? 0,
                        'on_hand_quantity' => $data['on_hand_quantity'] ?? 0
                    ]
                );
            }
        });

        Mycatalog::find($this->mycatelog_id)->update([
            'category_id' => $this->category_id,
            'product_price' =>  $this->product_cost,
            'product_cost' => $this->product_cost,
        ]);

        $this->dispatch('close-modal', 'edit-product-modal');
        $this->addNotification('Alert and On-Hand Quantity updated', 'success');
        $this->dispatch('pg:eventRefresh-master-catalog-list-wvwykb-table');
        $this->dispatch('pg:eventRefresh-my-products-list-fm2jer-table');
    }

    public function downloadSampleCsv()
    {
        $headers = [
            'supplier',
            'product_code',
            'product_name',
            'base_unit',
            'product_description',
            'product_cost',
            'category',
            'alert_quantity',
            'par_quantity'
        ];

        // Fetch products with their base unit
        $products = DB::table('products')
            ->join('product_units', 'products.id', '=', 'product_units.product_id')
            ->join('units', 'product_units.unit_id', '=', 'units.id')
            ->join('suppliers','suppliers.id','=','products.product_supplier_id')
            ->where('product_units.is_base_unit', true)
            ->select(
                'products.product_code',
                'products.product_name',
                'units.unit_name as base_unit',
                'products.product_description',
                'suppliers.supplier_name'
            )
            ->get();

        // Prepare CSV content
        $csv = implode(',', $headers) . "\n";

        foreach ($products as $product) {
            $csvRow = [
                $product->supplier_name,
                $product->product_code,
                $product->product_name,
                $product->base_unit,
                $product->product_description,
                '', // Empty product_cost
                '', // Empty category
                '', // Empty alert_quantity
                ''  // Empty par_quantity
            ];

            $csv .= implode(',', array_map(function ($value) {
                // Escape any commas in the values
                return str_contains($value, ',') ? '"' . $value . '"' : $value;
            }, $csvRow)) . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'master_catalog_import.csv');
    }

    public function render()
    {
        return view('livewire.organization.catalog-component');
    }
}
