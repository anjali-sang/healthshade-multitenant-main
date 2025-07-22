<?php

namespace App\Livewire\Tables\Organization\Customer;

use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class CustomerList extends PowerGridComponent
{
    public string $tableName = 'customer-list-l0gx9s-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('customer_name','Action'),
        ];
    }

    public function datasource(): Builder
    {
        return Customer::query()->where('customer_is_active', true);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('customer_name')
            ->add('customer_email')
            ->add('customer_phone')
            ->add('customer_address')
            ->add('customer_city')
            ->add('customer_state')
            ->add('customer_pin_code')
            ->add('customer_country')
            ->add('customer_is_active')
            ->add('created_at')
            ->add(
                'custom_address',
                fn($row) =>
                $row->customer_address . ', ' .
                $row->customer_city . ', ' .
                $row->customer_state . ' - ' .
                $row->customer_pin_code . ', ' .
                $row->customer_country
            );
        
    }

    public function columns(): array
    {
        return [
            Column::make('Customer', 'customer_name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'customer_email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'customer_phone')
                ->sortable()
                ->searchable(),

            Column::make('Customer address', 'custom_address')
                ->sortable()
                ->searchable(),


            Column::make('Created at', 'created_at')
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
    public function actions(Customer $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('openEditModal', ['customerId' => $row->id])
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
