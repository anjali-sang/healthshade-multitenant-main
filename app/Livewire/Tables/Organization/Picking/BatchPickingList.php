<?php

namespace App\Livewire\Tables\Organization\Picking;

use App\Models\BatchInventory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class BatchPickingList extends PowerGridComponent
{
    public string $tableName = 'batch-picking-list-ga40i5-table';

    public $selectedLocation = '';

    protected $listeners = ['pickingLocationChanged' => 'updateLocation'];

    public function boot(): void
    {
        // config(['livewire-powergrid.filter' => 'outside']);
        if ($this->selectedLocation == '') {
            // Set the default location to the user's location if not set
            $this->selectedLocation = auth()->user()->location_id ?? '';
        }

    }

    public function updateLocation($locationId)
    {
        $this->selectedLocation = $locationId;
        logger()->info('Picking location changed to: ' . $this->selectedLocation);
        $this->resetPage();
    }


    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->withoutLoading(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()->fixedColumns('product_name','Action'),
        ];
    }

    public function datasource(): Builder
    {
        $query = BatchInventory::query()
            ->where('batch_inventories.organization_id', auth()->user()->organization_id)
            ->join('products', function ($join) {
                $join->on('products.id', '=', 'batch_inventories.product_id')
                    ->where('products.is_active', '=', 1)
                    ->where('products.has_expiry_date', '=', 1);
            })
            ->where('batch_inventories.quantity', '>', 0)
            ->join('product_units', function ($join) {
                $join->on('product_units.product_id', '=', 'products.id')
                    ->where('product_units.is_base_unit', '=', 1);
            })
            ->join('units', 'units.id', '=', 'product_units.unit_id')
            ->select(
                'batch_inventories.*',
                'products.product_code as product_code',
                'products.product_name as product_name',
                'units.unit_name as base_unit_name',
            );

        if ($this->selectedLocation != '') {
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
            ->add('product_id')
            ->add('product_code')
            ->add('base_unit_name')
            ->add('product_name')
            ->add('quantity')
            ->add('batch_number')
            ->add('expiry_date_formatted', function ($model) {
                return $model->expiry_date
                    ? date('m/Y', strtotime($model->expiry_date))
                    : null;
            })

            ->add('organization_id')
            ->add('location_id')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [



            Column::make('Code', 'product_code'),
            Column::make('Product', 'product_name')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Base Unit', 'base_unit_name')
                ->sortable()
                ->searchable(),

            Column::make('Available', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Batch number', 'batch_number')
                ->sortable()
                ->searchable(),
            Column::make('Expiry date', 'expiry_date_formatted', 'expiry_date')
                ->sortable(),


            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('batch_number')
                ->placeholder('Batch number')
                ->operators(['contains']),
            Filter::inputText('product_name')
                ->placeholder('Name')
                ->operators(['contains']),

            Filter::inputText('product_code')
                ->placeholder('Code')
                ->operators(['contains']),
            Filter::datepicker('expiry_date'),
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
                ->slot('Pick')
                ->id()
                ->class('inline-flex items-center justify-center w-24 px-4 py-2 bg-green-500 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-500 focus:bg-green-500 dark:focus:bg-green-500 active:bg-green-500 dark:active:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150')
                ->dispatch('pickBatchProduct', ['rowId' => $row->id])
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
