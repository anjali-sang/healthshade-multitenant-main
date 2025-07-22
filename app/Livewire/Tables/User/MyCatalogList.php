<?php

namespace App\Livewire\Tables\User;

use App\Models\Mycatalog;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class MyCatalogList extends PowerGridComponent
{
    public string $tableName = 'my-catalog-list-cnalxn-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
            ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('product_name','Action'),
        ];
    }

    public function datasource(): Builder
    {
        return Mycatalog::query()
            ->where('mycatalogs.organization_id', auth()->user()->organization_id)
            ->where('suppliers.is_active', true)
            ->leftJoin('products', 'products.id', '=', 'mycatalogs.product_id')
            ->leftJoin('suppliers', 'products.product_supplier_id', '=', 'suppliers.id')
            ->leftJoin('users as creators', 'products.created_by', '=', 'creators.id')
            ->leftJoin('users as updaters', 'products.updated_by', '=', 'updaters.id')
            ->leftjoin('categories', 'categories.id', 'products.category_id')
            ->select([
                'products.id',
                'products.product_code',
                'products.product_name',
                'products.image',
                'products.product_description',
                'suppliers.supplier_name',
                'categories.category_name',
                'creators.name as created_by_name',
                'updaters.name as updated_by_name',
                'products.cost',
                DB::raw("(SELECT units.unit_name 
                FROM product_units 
                LEFT JOIN units ON product_units.unit_id = units.id 
                WHERE product_units.product_id = products.id 
                AND product_units.is_base_unit = true 
                ORDER BY product_units.id ASC 
                LIMIT 1) as unit_name")
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('image', function ($item) {
                $images = json_decode($item->image, true);

                // Ensure $images is an array and not empty
                $imagePath = is_array($images) && !empty($images) ? $images[0] : $item->image;

                return '<img class="w-10 h-10 rounded-md" src="' . asset('storage/' . $imagePath) . '">';
            })
            ->add('unit_name')
            ->add('organization_id')
            ->add('supplier_name')
            ->add('product_id')
            ->add('category_id')
            ->add('product_cost')
            ->add('product_price')
            ->add('created_at')
            ->add('formatted_cost', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->cost, 2);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Image', 'image')->sortable()->searchable(),
            Column::make('Code', 'product_code')->sortable()->searchable(),
            Column::make('Name', 'product_name')->sortable()->searchable(),
            Column::make('Units', 'unit_name')->sortable()->searchable(),
            Column::make('Cost', 'formatted_cost')->sortable()->searchable(),
            Column::make('Category', 'category_name')->sortable()->searchable(),
            Column::make('Supplier', 'supplier_name')->sortable()->searchable(),
            Column::make('Description', 'product_description'),
            Column::action('Action')->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Mycatalog $row): array
    {
        return [
            
            Button::add('edit')
                ->slot('Edit: '.$row->id)
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
