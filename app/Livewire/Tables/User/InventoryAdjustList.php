<?php

namespace App\Livewire\Tables\User;

use App\Models\StockCount;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Models\Cart;
use App\Models\Location;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use App\Models\Mycatalog;
use App\Models\Product;
use App\Models\Supplier;
use PowerComponents\LivewirePowerGrid\Facades\Rule;

final class InventoryAdjustList extends PowerGridComponent
{
    public string $tableName = 'inventory-adjust-list-4akuef-table';

    public bool $showFilters = false;
    use WithExport;

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
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

            //     ->showSearchInput(),
            PowerGrid::footer()
            ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('product_name'),
        ];
    }

    public function detailView($row): string
{
    return view('livewire.tables.user.stock-detail-card', ['stock' => $row])->render();
}

    public function datasource(): Builder
    {
        $query = StockCount::query()
        ->join('products', 'products.id', '=', 'stock_counts.product_id')
        ->join('locations', function ($join) {
            $join->on('locations.id', '=', 'stock_counts.location_id')
                ->where('locations.org_id', auth()->user()->organization_id)
                ->where('locations.is_active', 1);
        })
        ->where('products.is_active',true)
        ->select(
            'stock_counts.*',
            'products.product_name as product_name',
            'products.product_code as product_code',
            'locations.name as location_name'
        );
    
        if (auth()->check() && auth()->user()->role_id == 3) {
            $query->where('stock_counts.location_id', auth()->user()->location_id);
        }

        return $query;
    }


    public function relationSearch(): array
    {
        return [
            // 'product' => ['product_name'],
            'location' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
        ->add('id')
        ->add('product_code')
        ->add('product_name')
        ->add('location_name')
        ->add('on_hand_quantity')
        ->add('par_quantity')
        ->add('alert_quantity')
        ->add('organization_id')
        ->add('created_at',function ($model) {
            return $model->created_at
                ? date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at))
                : null;
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'product_code'),
            Column::make('Product', 'product_name')->searchable()
            ->headerAttribute('', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('', 'min-width: 0; white-space: normal !important;'),
                Column::make('Location', 'location_name')
                ->sortable()
                ->searchable(),
            Column::make('Available', 'on_hand_quantity')
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
        $filters = [
            Filter::inputText('product_code')
                ->placeholder('Product Code')
                ->operators(['contains']),
            Filter::inputText('product_name')
                ->placeholder('Product Name')
                ->operators(['contains']),
        ];

        // Show location filter only if the user's role ID is 2
        if (auth()->check() && auth()->user()->role_id == 2) {
            $filters[] = Filter::select('location_name', 'location_id') // Match alias from `datasource()`
                ->dataSource(Location::where('org_id', auth()->user()->organization_id)->get())
                ->optionLabel('name')
                ->optionValue('id');
        }

        return $filters;
    }
    public function actions(StockCount $row): array
    {
        return [
            Button::add('edit')
            ->slot('Adjust')
            ->id()
            ->class('inline-flex items-center justify-center px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white dark:bg-primary-md uppercase tracking-widest hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 min-w-[80px]')
            ->dispatch('adjustProduct', ['rowId' => $row->id])
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
