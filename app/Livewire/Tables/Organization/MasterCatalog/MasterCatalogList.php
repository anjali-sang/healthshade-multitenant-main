<?php

namespace App\Livewire\Tables\Organization\MasterCatalog;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Mycatalog;
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

final class MasterCatalogList extends PowerGridComponent
{
    public string $tableName = 'master-catalog-list-wvwykb-table';
    public bool $showFilters = true;
    use WithExport;

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            // PowerGrid::exportable('export')
            //     ->striped()
            //     ->columnWidth([
            //         2 => 30,
            //     ])
            //     ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            // PowerGrid::header(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),

            PowerGrid::responsive()
                ->fixedColumns('product_name','Action'),
            // PowerGrid::responsive()
            //     ->fixedColumns(),

        ];
    }
    public function header(): array
    {
        return [
        ];
    }

    public function datasource(): Builder
    {
        // return Product::query()
        //     ->with(['brand'])
        //     ->where('products.organization_id', auth()->user()->organization_id)
        //     ->where('suppliers.is_active', true)
        //     ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        //     ->leftJoin('suppliers', 'products.product_supplier_id', '=', 'suppliers.id')
        //     ->leftJoin('users as creators', 'products.created_by', '=', 'creators.id')
        //     ->leftJoin('users as updaters', 'products.updated_by', '=', 'updaters.id')
        //     ->leftJoin('product_units', 'products.id', '=', 'product_units.product_id')->where('product_units.is_base_unit', true)
        //     ->leftJoin('units', 'product_units.unit_id', '=', 'units.id')
        //     ->select([
        //         'products.id',
        //         'products.brand_id',
        //         'products.product_code',
        //         'products.product_name',
        //         'products.product_description',
        //         'products.image',
        //         'products.cost',
        //         'products.price',
        //         'suppliers.supplier_name',
        //         'creators.name as created_by_name',
        //         'updaters.name as updated_by_name',
        //         'products.category_id',
        //         'categories.category_name',
        //     ])
        //     ->selectRaw('GROUP_CONCAT(units.unit_name SEPARATOR ", ") as unit_names')
        //     ->groupBy([
        //         'products.id',
        //         'products.brand_id',
        //         'products.product_code',
        //         'products.product_name',
        //         'products.cost',
        //         'products.price',
        //         'products.product_description',
        //         'products.image',
        //         'suppliers.supplier_name',
        //         'categories.category_name',
        //         'creators.name',
        //         'updaters.name',
        //         'products.category_id',
        //     ]);'
        return Product::query()->with(['brand', 'unit', 'organization', 'supplier', 'categories'])
            ->where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true);
    }


    public function relationSearch(): array
    {
        return [
            'brand' => ['brand_name'],
            'categories' => ['category_name'],
            'supplier' => ['supplier_name']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()->add('id')
            ->add('image', function ($item) {
                if (str_starts_with($item->image, 'http')) {
                    $fullImageUrl = $item->image;
                } else {
                    $images = json_decode($item->image, true);
                    $imagePath = is_array($images) && !empty($images) ? $images[0] : $item->image;
                    $fullImageUrl = asset('storage/' . $imagePath);
                }

                return '<div onclick="openImageModal(\'' . $fullImageUrl . '\')" class="cursor-pointer">
                <img class="w-10 h-10 rounded-md" src="' . $fullImageUrl . '">
            </div>';
            })
            ->add('product_name', function ($item) {
                return '<span
        class="underline cursor-pointer text-blue-600 hover:text-blue-800"
        onclick="openProductModal(\'' . e($item->id) . '\')">'
                    . e($item->product_name) .
                    '</span>';
            })
            ->add('product_code')
            ->add('brand_name', function ($item) {
                return ($item->brand?->brand_name);
            })
            ->add('unit_name', function ($item) {
                return optional($item->unit->first())->unit?->unit_name;
            })
            ->add('product_description')
            ->add('formatted_cost', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->cost, 2);
            })
            ->add('category_name', function ($item) {
                return ($item->categories?->category_name);
            })
            ->add('supplier_name', function ($item) {
                return ($item->supplier?->supplier_name);
            })
            // ->add('created_at', function ($model) {
            //     return $model->created_at
            //         ? date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at))
            //         : null;
            // })
        ;
    }

    public function columns(): array
    {
        return [
            Column::make('Image', 'image')
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Product Code', 'product_code')->sortable()->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Product Name', 'product_name')->sortable()->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Manufacturer', 'brand_name')->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Units', 'unit_name')->searchable(),
            Column::make('Cost', 'formatted_cost')->searchable(),
            Column::make('Category', 'category_name')->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            Column::make('Supplier', 'supplier_name')->searchable(),
            Column::make('Description', 'product_description')
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),
            // Column::make('Date', 'created_at')->sortable()->searchable(),
            Column::action('Action'),
        ];
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
            Filter::select('brand_name', 'products.brand_id')
                ->dataSource(collection: Brand::where('brand_is_active', true)->where('organization_id', auth()->user()->organization_id)->orderBy('brand_name', 'asc')->get())
                ->optionLabel('brand_name')
                ->optionValue('id'),
            Filter::select('category_name', 'products.category_id')
                ->dataSource(Category::where('categories.organization_id', auth()->user()->organization_id)->where('is_active', true)->orderBy('category_name', 'asc')->get())
                ->optionLabel('category_name')
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
                ->slot($isInCatalog ? 'Remove' : '+ Add')
                ->id()
                ->class(
                    'inline-flex items-center justify-center w-24 px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 ' .
                    ($isInCatalog
                        ? 'bg-red-500 hover:bg-red-600 active:bg-red-800 ring-red-300'
                        : 'bg-green-500 hover:bg-green-600 active:bg-green-800 ring-green-300')
                )
                ->dispatch('toggleMyCatalog', ['rowId' => $row->id])
            ,
        ];
    }

    // public function actionRules($row): array
    // {
    //     return [
    //         // Hide button edit for ID 1
    //         Rule::button('user')->when(fn() => auth()->user()->is_medical_rep)->hide(),
    //         // Rule::button('user')->when(fn() => auth()->user()->role_id == '1')->hide(),
    //     ];
    // }
}
