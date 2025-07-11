<?php

namespace App\Livewire\Tables\Reports;

use App\Models\InventoryTransfer;
use App\Models\Organization;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Models\InventoryAdjust;
use App\Models\Location;
use App\Models\PickingDetailsModel;
use App\Models\PickingModel;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class InventoryTransferReport extends PowerGridComponent
{
    public string $tableName = 'inventory-transfer-report-xf1yrk-table';
    use WithExport;

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
            PowerGrid::responsive()->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        $query = InventoryTransfer::query()
            ->with(['organization'])
            ->leftJoin('users', 'users.id', '=', 'inventory_transfers.user_id')
            ->leftJoin('products', 'products.id', '=', 'inventory_transfers.product_id')
            ->leftJoin('units', 'units.id', '=', 'inventory_transfers.unit_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'inventory_transfers.supplier_id')
            ->select(
                'inventory_transfers.*',
                'users.name as user_name',
                'products.product_name as product_name',
                'units.unit_name as unit_name',
                'suppliers.supplier_name as supplier_name',
            );
        if (auth()->user()->role_id != 1) {
            $query->where('inventory_transfers.organization_id', auth()->user()->organization_id);
        }
        return $query;
    }


    public function relationSearch(): array
    {
        return [
            // 'product' => [
            //     'product_name'
            // ],
            // 'unit' => ['unit_name'],
            // 'supplier' => ['supplier_name'],
            // 'organization' => ['organization_name'],
            // 'user' => ['name'],
            // 'fromLocation' => ['location_name'],
            // 'toLocation' => ['location_name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name', fn($item) => e($item->organization->name))
            ->add('reference_number')
            ->add('product_id')
            ->add('product_name')
            ->add('quantity')
            ->add('unit_id')
            ->add('unit_name')
            ->add('from_location_id')
            ->add('fromLocation.name')
            ->add('to_location_id')
            ->add('toLocation.name')
            ->add('supplier_id')
            ->add('organization_id')
            ->add('user_name')
            ->add('inventory_transfers.created_at', function ($model) {
                return date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at));
            });
    }

    public function columns(): array
    {
        $columns = [

            Column::make('Created at', 'inventory_transfers.created_at')
                ->sortable()
                ->searchable(),
            Column::make('Transfer number', 'reference_number')
                ->sortable()
                ->searchable(),
            Column::make('Product', 'product_name')
                ->sortable()
                ->searchable(),


            Column::make('Transfered Qty', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Unit ', 'unit_name')
                ->sortable()
                ->searchable(),

            Column::make('From', 'fromLocation.name')
            ->sortable()
            ->searchable(),

            Column::make('To', 'toLocation.name')
                ->sortable()
                ->searchable(),

            Column::make('User', 'user_name')
                ->sortable()
                ->searchable(),

            Column::action('Action')->hidden()
        ];
        if (auth()->user()->role_id == 1) {
            array_splice($columns, 2, 0, [
                Column::make('Organization', 'name')
                    ->sortable()
                    ->searchable()
                    ->bodyAttribute('class', 'w-12 text-xs '),
            ]);
        }

        return $columns;
    }

    public function filters(): array
    {
        return [
            Filter::inputText('reference_number')
            ->placeholder('Transfer number')
            ->operators(['contains']),
            Filter::datetimepicker('inventory_transfers.created_at'),
            Filter::inputText('unit_name')
            ->placeholder('Unit')
            ->operators(['contains']),
            Filter::inputText('product_name')
            ->placeholder('Product')
            ->operators(['contains']),
            Filter::inputText('user_name') // Use the actual column name
            ->placeholder('User')
            ->operators([
                'contains',
                'contains_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::select('name', 'inventory_transfers.organization_id')
            ->dataSource(Organization::all())
            ->optionLabel('name')
            ->optionValue('id'),
            Filter::select('fromLocation.name', 'from_location_id')
            ->dataSource(Location::all()) 
            ->optionLabel('name')
            ->optionValue('id'),
            Filter::select('toLocation.name', 'to_location_id')
            ->dataSource(Location::all()) 
            ->optionLabel('name')
            ->optionValue('id'),
            Filter::inputText('quantity')
            ->placeholder('Qty')
            ->operators(['contains']),

        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(InventoryTransfer $row): array
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
