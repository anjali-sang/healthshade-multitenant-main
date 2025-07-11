<?php

namespace App\Livewire\Tables\Organization\Shipment;

use App\Models\Shipment;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ShipmentList extends PowerGridComponent
{
    public string $tableName = 'shipment-list-uyko3k-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(50),
        ];
    }

    public function datasource(): Builder
    {
        return Shipment::query()->with(['user','customer','location']);
    }

    public function relationSearch(): array
    {
        return [
            'customer' => [
                'customer_name',
            ],
            'user' => [
                'name',
            ],
            'location' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('shipment_number')
            ->add('user',function($model){
                return $model->user->name;
            })
            ->add('customer',function($model){
                return $model->customer->customer_name;
            })
            ->add('location',function($model){
                return $model->location->name;
            })
            ->add('total_price',function($model){
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($model->total_price, 2);
            })
            ->add('created_at',function($model) {
                return Carbon::parse($model->created_at)->format( 'm/d/Y');
            });
    }

    public function columns(): array
    {
        return [

            Column::make('Created at', 'created_at')
                ->sortable(),

            Column::make('Shipping', 'shipment_number')
                ->sortable(),

            Column::make('Customer', 'customer')
                ->sortable()
                ->searchable(),

            Column::make('Location', 'location')
                ->sortable()
                ->searchable(),

            Column::make('Total', 'total_price')
                ->sortable()
                ->searchable(),

            Column::make('Created by', 'user')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }


    public function actions(Shipment $row): array
    {
        return [
            Button::add('view')
                ->slot('View')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('view', ['saleId' => $row->id])
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
