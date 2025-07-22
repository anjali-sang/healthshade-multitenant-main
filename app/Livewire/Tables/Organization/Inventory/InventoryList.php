<?php

namespace App\Livewire\Tables\Organization\Inventory;

use App\Models\Cart;
use App\Models\Location;
use App\Models\StockCount;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class InventoryList extends PowerGridComponent
{
    public string $tableName = 'inventory-list-aftzfa-table';
    public bool $showFilters = false;
    use WithExport;
    public array $alert_quantity = [];
    public array $par_quantity = [];
    public bool $showErrorBag = true;

    public $selectedLocation = '';
    protected $listeners = ['inventoryIocationChanged' => 'updateLocation'];

    public function updateLocation($locationId)
    {
        $this->selectedLocation = $locationId;
        $this->resetPage();
    }

    public function boot(): void
    {
        if (!$this->selectedLocation) {
            $this->selectedLocation = auth()->user()->location_id ?? null;
        }

    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('export')
                ->striped()
                ->columnWidth([
                    2 => 30,
                ])
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showToggleColumns(),

            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),

            PowerGrid::responsive()
                 ->fixedColumns('product_name','Action'),
        ];
    }

    public function datasource(): Builder
    {
        $query = StockCount::query()
            ->join('products', 'products.id', '=', 'stock_counts.product_id')
            ->join('locations', 'locations.id', '=', 'stock_counts.location_id')
            ->join('suppliers', 'suppliers.id', '=', 'products.product_supplier_id')
            ->join('product_units', function ($join) {
                $join->on('product_units.product_id', '=', 'products.id')
                    ->where('product_units.is_base_unit', 1);
            })
            ->join('units', 'units.id', '=', 'product_units.unit_id')
            ->where('suppliers.is_active', true)
            ->where('products.is_active', true)
            ->where('products.organization_id', auth()->user()->organization_id)
            ->where('locations.org_id', auth()->user()->organization_id)
            ->select(
                'stock_counts.*',
                'products.id as product_id',
                'products.image as product_image',
                'products.product_name as product_name',
                'products.product_code as product_code',
                'locations.name as location_name',
                'units.unit_name as base_unit_name',
                'suppliers.supplier_name as supplier_name',
                'suppliers.supplier_slug as supplier_slug',
            );

        if ($this->selectedLocation) {
            $query->where('stock_counts.location_id', $this->selectedLocation);
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'product' => ['product_name'], // Ensure 'product_name' is searchable
            'location' => ['name'], // Ensure 'name' from location is searchable
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('product_image', function ($item) {
                // Check if product_image starts with "http"
                if (str_starts_with($item->product_image, 'http')) {
                    $fullImageUrl = $item->product_image;
                } else {
                    $images = json_decode($item->product_image, true);

                    // Ensure $images is an array and not empty
                    $imagePath = is_array($images) && !empty($images) ? $images[0] : $item->product_image;
                    $fullImageUrl = asset('storage/' . $imagePath);
                }

                return '<div onclick="openImageModal(\'' . $fullImageUrl . '\')" class="cursor-pointer">
                <img class="w-10 h-10 rounded-md" src="' . $fullImageUrl . '">
            </div>';
            })
            ->add('base_unit_name')
            ->add('product_name', function ($item) {
                return '<span
        class="underline cursor-pointer text-blue-600 hover:text-blue-800"
        onclick="openProductModal(\'' . e($item->product_id) . '\')">'
                    . e($item->product_name) .
                    '</span>';
            })
            ->add('product_code')
            ->add('supplier_name')
            ->add('location_name')
            ->add('on_hand_quantity')
            ->add('par_quantity')
            ->add('alert_quantity')
            ->add('organization_id')
            ->add('formatted_created_at', fn($item) => date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($item->created_at)));
    }

    public function columns(): array
    {
        return [
            Column::make('Image', 'product_image')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Code', 'product_code')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Product', 'product_name')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Supplier', 'supplier_name')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Location', 'location_name')->hidden(),

            Column::make('Available', 'on_hand_quantity')
                ->sortable()
                ->searchable(),

            Column::make('Base Unit', 'base_unit_name')
                ->sortable()
                ->searchable(),

            Column::make('Alert', 'alert_quantity')
                ->sortable()
                ->searchable()
                ->editOnClick(true),

            Column::make('Par', 'par_quantity')
                ->sortable()
                ->searchable()
                ->editOnClick(true),

            Column::make('Created at', 'formatted_created_at')
                ->sortable()
                ->searchable()
                ->hidden(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        $filters = [
            Filter::inputText('product_name')
                ->placeholder('Product Name')
                ->operators(['contains']),
            Filter::inputText('product_code')
                ->placeholder('Code')
                ->operators(['contains']),
            Filter::select('supplier_name', 'products.product_supplier_id')
                ->dataSource(Supplier::orderBy('supplier_name', 'asc')->get())
                ->optionLabel('supplier_name')
                ->optionValue('id'),
        ];

        return $filters;
    }

    protected function rules()
    {
        return [
            'alert_quantity.*' => ['required', 'numeric', 'min:0'],
            'par_quantity.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function messages()
    {
        return [
            'alert_quantity.*.required' => 'Alert quantity is required',
            'alert_quantity.*.numeric' => 'Alert quantity must be a number',
            'alert_quantity.*.min' => 'Alert quantity must be at least 0',
            'par_quantity.*.required' => 'Par quantity is required',
            'par_quantity.*.numeric' => 'Par quantity must be a number',
            'par_quantity.*.min' => 'Par quantity must be at least 0',
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        // Validate the input
        if ($field === 'alert_quantity' || $field === 'par_quantity') {
            // Clean value and ensure it's numeric
            $cleanValue = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            if (!is_numeric($cleanValue)) {
                $this->addError($field, 'Must be a valid number');
                $this->dispatch('pg:skipNextRefresh');
                return;
            }

            // Update the database
            try {
                StockCount::query()->findOrFail($id)->update([
                    $field => (float) $cleanValue,
                ]);
                // Success notification
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' updated successfully!'
                ]);
            } catch (\Exception $e) {
                // Error notification
                $this->addError($field, 'Failed to update: ' . $e->getMessage());
                $this->dispatch('pg:skipNextRefresh');
            }
        }
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(StockCount $row): array
    {
        $inv = StockCount::where('id', $row->id)->first();
        $existingCartItem = Cart::where('product_id', $inv->product_id)
            ->where('organization_id', auth()->user()->organization_id)
            ->where('location_id', $inv?->location_id)
            ->first();

        return [
            Button::add('cartIconClick')
                ->slot(
                    $existingCartItem
                    ? 'Remove'
                    : '<svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
                </svg>',
                )
                ->id()
                ->class(($existingCartItem ? 'bg-red-400 ' : '') . 'inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white dark:bg-green-500 uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-500 focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150')
                ->dispatch('cartIconClick', ['rowId' => $row->id])
        ];
    }
}