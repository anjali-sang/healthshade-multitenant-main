<?php

namespace App\Livewire\Tables\Admin;

use App\Models\Mycatalog;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Supplier;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ProductsList extends PowerGridComponent
{
    public string $tableName = 'products-list-ou8dim-table';
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
        ->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query()
            ->with([
                'organization','supplier'
            ])
            ->leftJoin('product_units', 'products.id', '=', 'product_units.product_id')
            ->leftJoin('units', 'product_units.unit_id', '=', 'units.id')
            ->select([
                'products.id',
                'products.product_supplier_id',
                'products.organization_id',
                'products.product_code',
                'products.product_name',
                'products.product_description',
                'products.image',
            ])
            ->selectRaw('GROUP_CONCAT(units.unit_name SEPARATOR ", ") as unit_names')
            ->groupBy([
                'products.id',
                'products.product_code',
                'products.product_name',
                'products.product_description',
                'products.image',
            ]);
    }


    public function relationSearch(): array
    {
        return [
            'organization' => [
                'name',
            ],
            'supplier' => [
                'supplier_name',
            ],
        ];
    }


    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('products.id')
            ->add('image', function ($item) {
                $images = json_decode($item->image, true);

                // Ensure $images is an array and not empty
                $imagePath = is_array($images) && !empty($images) ? $images[0] : $item->image;

                return '<img class="w-10 h-10 rounded-md" src="' . asset('storage/' . $imagePath) . '">';
            })
            ->add('products.product_name')
            ->add('name', fn($item) => $item->organization?->name)
            ->add('products.product_code')
            ->add('product_supplier_id')
            ->add('products.supplier_name',fn($item) => $item->supplier?->supplier_name)
            ->add('unit_names')
            ->add('created_at');
    }


    public function columns(): array
    {
        $columns = [
            Column::make('Image', 'image')->sortable()->searchable(),
            Column::make('Code', 'product_code')->sortable()->searchable(),
            Column::make('Name', 'product_name')->sortable()->searchable(),
            Column::make('Supplier', 'supplier_name')->sortable()->searchable(),
            Column::make('Units', 'unit_names')->sortable()->searchable(),
            Column::make('Organization', 'name')->sortable()->searchable(),
        ];
        $columns[] = Column::action('Action');
        return $columns;
    }

    public function filters(): array
    {
        return [
            Filter::inputText('product_name')->placeholder('Name')->operators(['contains']),
            Filter::inputText('product_code')->placeholder('Code')->operators(['contains']),
            Filter::select('supplier_name', 'products.product_supplier_id')
                ->dataSource(Supplier::orderBy('supplier_name', 'asc')->get())
                ->optionLabel('supplier_name')
                ->optionValue('id'),
            Filter::select('name', 'products.organization_id')
                ->dataSource(Organization::orderBy('name', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),

        ];
    }

    public function actions(Product $row): array
    {
        $org_id = auth()->user()->organization_id;
        $isInCatalog = Mycatalog::where('organization_id', $org_id)
            ->where('product_id', $row->id)
            ->exists();
        return [
            Button::add('edit')
                ->slot('
                <span class="w-24 flex justify-center items-center relative"
                      x-data="{ loading: false }"
                      x-on:click="loading = true; $dispatch(\'edit-product\', { rowId: ' . $row->id . ' }); setTimeout(() => loading = false, 1000)">

                    <!-- Normal state: Icon + Edit text -->
                    <span class="flex items-center gap-2" :class="{\'invisible\': loading}">
                        <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z"/>
                        </svg>
                        Edit
                    </span>

                    <!-- Loading state: Spinner -->
                    <span x-show="loading" class="absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </span>
            ')
                ->id('edit-btn-' . $row->id)
                ->class('inline-flex items-center justify-center w-24 px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150')
                ->class('hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800')
                ->dispatch('edit-product', ['rowId' => $row->id]),

            Button::add('user')
                ->slot(
                    $isInCatalog
                    ? 'Remove'
                    : 'Add',
                )
                ->id()
                ->class($isInCatalog ? 'inline-flex items-center px-4 py-2 bg-red-500 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white dark:bg-primary-md uppercase tracking-widest hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-lt focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150' : 'inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white dark:bg-primary-md uppercase tracking-widest hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150')
                ->dispatch('toggleMyCatalog', ['rowId' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            // Hide button edit for ID 1
            Rule::button('edit')->when(fn() => auth()->user()->role_id == '2')->hide(),
            Rule::button('user')->when(fn() => auth()->user()->role_id == '1')->hide(),
        ];
    }
}
