<?php

namespace App\Livewire\Tables\Reports;

use App\Models\BatchPicking;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Models\Location;
use App\Models\Organization;
use App\Models\PickingDetailsModel;
use App\Models\PickingModel;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class BatchPickingReport extends PowerGridComponent
{
    public string $tableName = 'batch-picking-report-f6czb0-table';

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
            PowerGrid::responsive()
                ->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        return BatchPicking::query()
            ->with(['organization'])
            ->leftJoin('products', 'batch_pickings.product_id', '=', 'products.id')
            ->leftJoin('locations', 'batch_pickings.location_id', '=', 'locations.id')
            ->leftJoin('users', 'batch_pickings.user_id', '=', 'users.id')
            ->select(
                'batch_pickings.*',
                'products.product_name as product_name',
                'products.product_code as product_code',
                'locations.name as location_name',
                'users.name as user_name',
            );
    }

    public function relationSearch(): array
    {
        return [
            'organization' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('picking_number')
            ->add('location_name', function ($model) {
                return $model->location_name ?? 'N/A'; // Fetch location name
            })
            ->add('batch_id')
            ->add('organization_id')
            ->add('user_name', function ($model) {
                return $model->user_name ?? 'N/A'; // Fetch location name
            })
            ->add('product_name', function ($model) {
                return $model->product_name ?? 'N/A';
            })
            ->add('product_code', function ($model) {
                return $model->product_code ?? 'N/A';
            })
            ->add('picking_quantity')
            ->add('picking_unit')
            ->add('net_unit_price')
            ->add('total_amount', function ($model) {
                return session('currency', '$') . ' ' . number_format($model->total_amount, 2);
            })
            ->add('chart_number')
            ->add('batch_pickings.created_at', function ($model) {
                return date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at));
            });
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Created at', 'batch_pickings.created_at')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Picking number', 'picking_number')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Chart number', 'chart_number')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Location', 'location_name')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Batch(Lot#)', 'batch_id')
                ->sortable()
                ->searchable()->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Code', 'product_code')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Product', 'product_name')
                ->sortable()
                ->searchable()->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Picked', 'picking_quantity')
                ->sortable()
                ->searchable()->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Picking unit', 'picking_unit')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Total amount', 'total_amount')
                ->sortable()
                ->searchable()->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('User', 'user_name')
                ->sortable()
                ->searchable(),

            Column::action('Action')->hidden(),
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
            Filter::datetimepicker('batch_pickings.created_at'),
            Filter::inputText('picking_number')->placeholder('Picking Number')->operators(['contains']),
            Filter::inputText('chart_number')->placeholder('Chart Number')->operators(['contains']),
            Filter::select('location_name', 'batch_pickings.location_id')
                ->dataSource(Location::where('is_active', 1)->where('org_id', auth()->user()->organization_id)->get()) // Filter locations
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::inputText('batch_id')->placeholder('Batch')->operators(['contains']),
            Filter::inputText('product_code')->placeholder('Code')->operators([
                'contains',
                'contains_not',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::inputText('product_name')->placeholder('Product')->operators([
                'contains',
                'contains_not',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::inputText('picking_quantity')->placeholder('Picked')->operators(['contains']),
            Filter::inputText('picking_unit', 'picking_unit') // Match the field name and column name
                ->placeholder('Picking Unit')
                ->operators(['contains']),

            Filter::number('total_amount')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(BatchPicking $row): array
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
