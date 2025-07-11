<?php

namespace App\Livewire\Tables\Organization\Inventory;

use App\Models\BatchInventory;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class BatchInventoryTable extends PowerGridComponent
{
    public string $tableName = 'batch-inventory-table-du8flb-table';

    public $selectedLocation = '';
    protected $listeners = ['batchinventoryIocationChanged' => 'updateLocation'];

    public function updateLocation($locationId)
    {
        $this->selectedLocation = $locationId;
        $this->resetPage();
    }

    public function boot(): void
    {
        $this->selectedLocation = auth()->user()->location_id ?? null;
    }
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive(),
        ];
    }

    public function datasource(): Builder
    {
        $query = BatchInventory::query()
            ->join('products', 'batch_inventories.product_id', '=', 'products.id')
            ->join('suppliers', 'products.product_supplier_id', '=', 'suppliers.id')
            ->leftJoin('locations', 'batch_inventories.location_id', '=', 'locations.id')
            ->select(
                'batch_inventories.*',
                'products.id as product_id',
                'products.product_name',
                'products.product_code',
                'products.product_supplier_id',
                'products.image as product_image',
                'suppliers.supplier_name',
                'locations.name as location_name'
            )
            ->where('batch_inventories.organization_id', auth()->user()->organization_id);

        if ($this->selectedLocation) {
            $query->where('batch_inventories.location_id', $this->selectedLocation);
        }
        return $query;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('product_image', function ($item) {
                $images = json_decode($item->product_image, true);
                // Ensure $images is an array and not empty
                $imagePath = is_array($images) && !empty($images) ? $images[0] : $item->image;
                $fullImageUrl = asset('storage/' . $imagePath);

                return '<div onclick="openImageModal(\'' . $fullImageUrl . '\')" class="cursor-pointer">
                            <img class="w-10 h-10 rounded-md" src="' . $fullImageUrl . '">
                        </div>';
            })
            ->add('product_name', function ($item) {
                return '<span
        class="underline cursor-pointer text-blue-600 hover:text-blue-800"
        onclick="openProductModal(\'' . e($item->product_id) . '\')">'
                    . e($item->product_name) .
                    '</span>';
            })
            ->add('product_code')
            ->add('supplier_name')
            ->add('base_unit', function ($model) {
                // Load the unit relationship directly in the query
                return optional($model->product->unit->first()?->unit)->unit_name ?? '';
            })
            ->add('quantity')
            ->add('location_name')
            ->add('batch_number')
            ->add('expiry_date_formatted', function ($model) {
                return Carbon::parse($model->expiry_date)->format( 'm/Y');
            })
            ->add('organization_id')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Image', 'product_image')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Code', 'product_code')
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Product', 'product_name')
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Supplier', 'supplier_name')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Available', 'quantity')
                ->sortable()
                ->searchable(),
            Column::make('Base Unit', 'base_unit')
                ->sortable()
                ->searchable(),
            Column::make('Lot #', 'batch_number')
                ->sortable()
                ->searchable(),
            Column::make('Expiry date', 'expiry_date_formatted', 'expiry_date')
                ->sortable(),
            Column::make('Location', 'location_name')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;')
                ->hidden(),
            Column::action('Action')->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('product_code')
                ->placeholder('Code')
                ->operators(['contains']),
            Filter::inputText('product_name')
                ->placeholder('Product')
                ->operators(['contains']),
            Filter::inputText('batch_number')
                ->placeholder('Batch number')
                ->operators(['contains']),
            Filter::datepicker('expiry_date'),
            Filter::select('supplier_name', 'product_supplier_id')
                ->dataSource(Supplier::all())
                ->optionLabel('supplier_name')
                ->optionValue('id'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(BatchInventory $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}