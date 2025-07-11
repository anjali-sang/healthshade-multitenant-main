<?php

namespace App\Livewire\Tables\Reports;

use App\Models\Location;
use App\Models\Organization;
use App\Models\PickingDetailsModel;
use App\Models\PickingModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;


final class PickingList extends PowerGridComponent
{
    public string $tableName = 'picking-list-over1t-table';

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


    public function datasource()
    {
        $query = PickingModel::query()
            ->with(['organization'])
            ->leftJoin('picking_details', 'pickings.id', '=', 'picking_details.picking_id')
            ->leftJoin('products', 'picking_details.product_id', '=', 'products.id')
            ->leftJoin('locations', 'pickings.location_id', '=', 'locations.id')
            ->leftJoin('users', 'pickings.user_id', '=', 'users.id')
            ->select(
                'pickings.id',
                'pickings.organization_id',
                'pickings.picking_number',
                'pickings.created_at',
                'locations.name as location_name',
                'users.name as user_name',
                'products.product_name',
                'picking_details.picking_unit as picking_unit',
                'picking_details.picking_quantity as picking_quantity',
                'picking_details.sub_total as total_price',
            );

        if (auth()->user()->role_id != 1) {
            $query->where('pickings.organization_id', auth()->user()->organization_id);
        }

        return $query;
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
            ->add('pickings.created_at', function ($model) {
                return date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at));
            })
            ->add('name', fn($item) => e($item->organization->name))
            ->add('picking_number')
            ->add('location_name', function ($model) {
                return $model->location_name ?? 'N/A'; // Fetch location name
            })
            ->add('product_name', function ($model) {
                return $model->pickingDetails->map(function ($detail) {
                    return optional($detail->product)->product_name;
                })->filter()->implode(', ') ?? 'N/A';
            })
            ->add('unit', function ($model) {
                return optional($model->pickingDetails)->pluck('picking_unit')->implode(', ') ?? 'N/A'; // Fetch unit
            })
            ->add('picking_quantity')
            ->add('total_price', function ($model) {
                return session('currency', '$') . ' ' . number_format($model->total_price, 2);
            })

            ->add('user_name', function ($model) {
                return $model->user_name ?? 'N/A'; // Fetch location name
            });
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Created at', 'pickings.created_at')
                ->sortable()
                ->searchable(),
            Column::make('Picking number', 'picking_number')
                ->sortable()
                ->searchable(),

            Column::make('Location', 'location_name') // Show location name
                ->sortable()
                ->searchable(),
            Column::make('Product Name', 'product_name') // Added Product Name
                ->sortable()
                ->searchable(),
            Column::make('Unit', 'unit') // Added Unit
                ->sortable()
                ->searchable(),
            Column::make('Quantity', 'picking_quantity') // Added Quantity
                ->sortable()
                ->searchable(),
            Column::make('Total Price', 'total_price') // Corrected column for total price
                ->sortable()
                ->searchable(),
            Column::make('User', 'user_name') // Show location name
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
            Filter::inputText('picking_number')->placeholder('Picking Number')->operators(['contains']),
            
            Filter::boolean('is_active')->label('Active', 'Inactive'),
            
            Filter::select('location_name', 'pickings.location_id')
                ->dataSource(Location::where('is_active', 1)->where('org_id', auth()->user()->organization_id)->get()) // Filter locations
                ->optionLabel('name')
                ->optionValue('id'),
            
                Filter::datetimepicker('pickings.created_at'),

            Filter::number('total_price', 'picking_details.sub_total')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

            Filter::number('picking_quantity')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('Min', 'Max'),

            Filter::inputText('product_name')->placeholder('Product')->operators([
                'contains',
                'contains_not',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            
            Filter::select('name', 'pickings.organization_id')
                ->dataSource(Organization::all())
                ->optionLabel('name')
                ->optionValue('id'),
            
                Filter::inputText('user_name', 'users.name') // Use the actual column name
                ->placeholder('User')
                ->operators([
                    'contains',
                    'contains_not',
                    'starts_with',
                    'ends_with',
                ]),

            Filter::inputText('unit', 'picking_unit') // Match the field name and column name
                ->placeholder('Picking Unit')
                ->operators(['contains']),

        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(PickingModel $row): array
    {
        return [


            Button::add('view')
                ->slot('Details')
                ->id($row->picking_id)
                ->class('text-primary-dk font-semibold')
                ->dispatch('rowClicked', ['id' => $row->id])


            // Button::add('edit')
            //     ->slot('
            //         <span class="min-w-8 cursor-pointer" x-data="{ loading: false }" x-on:click="loading = true; $wire.call(\'startEdit\', ' . $row->id . '); setTimeout(() => loading = false, 1000)">
            //             <span x-show="!loading">Edit</span>
            //             <span x-show="loading" class="flex items-center justify-center">
            //                 <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            //                     <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            //                     <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
            //                 </svg>
            //             </span>
            //         </span>
            //     ')
            //     ->id('edit-btn-' . $row->id)
            //     ->class('inline-flex items-center px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800')
            //     ->dispatch('edit-picking', ['rowId' => $row->id]) // Dispatch the edit-supplier event
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
