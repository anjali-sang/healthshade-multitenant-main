<?php

namespace App\Livewire\Tables\Organization\Picking;

use App\Models\Location;
use App\Models\StockCount;
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
use App\Models\Mycatalog;
use App\Models\Product;
use App\Models\Supplier;
use PowerComponents\LivewirePowerGrid\Facades\Rule;


final class PickingInventoryList extends PowerGridComponent
{
    public string $tableName = 'picking-inventory-list-q7yfsl-table';
    public bool $showFilters = false;

    public $selectedLocation = '';

    protected $listeners = ['pickingLocationChanged' => 'updateLocation'];

    use WithExport;
    public function boot(): void
    {
        // config(['livewire-powergrid.filter' => 'outside']);
        $this->selectedLocation = auth()->user()->location_id ?? null;
    }

    public function updateLocation($locationId)
    {
        $this->selectedLocation = $locationId;
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
            PowerGrid::responsive()
            ->fixedColumns('product_name','Action'),
        ];
    }

    public function datasource(): Builder
    {
        $query = StockCount::query()
            ->where('stock_counts.organization_id', auth()->user()->organization_id)
            ->join('products', function ($join) {
                $join->on('products.id', '=', 'stock_counts.product_id')
                    ->where('products.is_active', '=', 1)
                    ->where('products.has_expiry_date', '=', 0);
            })
            ->join('locations', 'locations.id', '=', 'stock_counts.location_id')
            ->where('stock_counts.on_hand_quantity', '>', 0)
            ->join('product_units', function ($join) {
                $join->on('product_units.product_id', '=', 'products.id')
                    ->where('product_units.is_base_unit', '=', 1);
            })
            ->join('units', 'units.id', '=', 'product_units.unit_id')
            ->select(
                'stock_counts.*',
                'products.product_code as product_code',
                'products.product_name as product_name',
                'locations.name as location_name',
                'units.unit_name as base_unit_name',
            );

        if ($this->selectedLocation) {
            $query->where('stock_counts.location_id', $this->selectedLocation);
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            // 'product' => [
            //     'product_name',
            // ],
            // 'location' => [
            //     'name',
            // ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('product_code')
            ->add('base_unit_name')
            ->add('product_name')
            ->add('location.name')
            ->add('on_hand_quantity')
            ->add('par_quantity')
            ->add('alert_quantity')
            ->add('organization_id')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'product_code'),
            Column::make('Product', 'product_name'),
            Column::make('Available', 'on_hand_quantity')
                ->sortable()
                ->searchable(),
            Column::make('Base Unit', 'base_unit_name')
                ->sortable()
                ->searchable(),
            Column::make('Alert', 'alert_quantity')
                ->sortable()
                ->hidden()
                ->searchable(),
            Column::make('Par', 'par_quantity')
                ->sortable()
                ->hidden()
                ->searchable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('product_name')
                ->placeholder('Name')
                ->operators(['contains']),

            Filter::inputText('product_code')
                ->placeholder('Code')
                ->operators(['contains']),

            Filter::select('location_id', 'location.id') // Ensure correct field reference
                ->dataSource(Location::where('org_id', auth()->user()->organization_id)->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }


    public function actions(StockCount $row): array
    {
        return [
            Button::add('edit')
                ->slot('Pick')
                ->id()
                ->class('inline-flex items-center justify-center w-24 px-4 py-2 bg-green-500 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-500 focus:bg-green-500 dark:focus:bg-green-500 active:bg-green-500 dark:active:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150')
                ->dispatch('pickProduct', ['rowId' => $row->id])
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