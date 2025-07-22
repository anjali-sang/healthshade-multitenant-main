<?php

namespace App\Livewire\Tables;

use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use App\Helpers\PowerGridThemes\TailwindStriped;

final class SubscriptionsList extends PowerGridComponent
{
    public string $tableName = 'subscriptions-list-mlwfa0-table';
    public bool $showFilters = false;
    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

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

            // PowerGrid::header()
            //     ->showSearchInput()
            //     ->showToggleColumns(),
            PowerGrid::footer()
            ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()->fixedColumns('name','Action'),
        ];
    }

    public function datasource(): Builder
    {
        return Subscription::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('formatted_price', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->price, 2);
            })             ->add('max_users')
            ->add('max_locations')
            ->add('created_at')
            ->add('is_active', fn($item) => $item->is_active ? '<div class="text-green-400">Active</div>' : '<div class="text-red-400">Inactive</div>')
            ->add('updated_at')
            ->add(
                'duration',
                fn($item) =>
                $item->duration == 12 ? '<div>1 Year</div>' :
                ($item->duration == 3 ? '<div>3 Months</div>' :
                    ($item->duration == 6 ? '<div>6 Months</div>' : '<div>Unknown</div>'))
            )
            ->add('created_by')
            ->add('updated_by')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Price', 'formatted_price')
                ->sortable()
                ->searchable(),

            Column::make('Max users', 'max_users')
                ->sortable()
                ->searchable(),

            Column::make('Duration', 'duration')
                ->sortable()
                ->searchable(),

            Column::make('Max locations', 'max_locations')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable()
                ->headerAttribute(' max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute(' max-w-xl', 'min-width: 0; white-space: normal !important;'),


            Column::make('Active', 'is_active')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->placeholder('Name')->operators(['contains']),
            Filter::boolean('is_active')->label('Active', 'Inactive'),
        ];
    }

    public function actions(Subscription $row): array
    {
        return [
            Button::add('edit')
                ->slot('
            <span class="min-w-8 flex items-center justify-center w-full" x-data="{ loading: false }" x-on:click="loading = true; $dispatch(\'edit\', { rowId: ' . $row->id . ' }); setTimeout(() => loading = false, 1000)">
                <span x-show="!loading" class="flex items-center">
                    <svg class="h-4 w-4 mr-1 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z"/>
                    </svg>
                    Edit
                </span>
                <span x-show="loading" class="flex items-center justify-center w-full">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                    </svg>
                </span>
            </span>
        ')
                ->id('edit-btn-' . $row->id) // Unique ID for each button
                ->class('inline-flex items-center justify-center px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white dark:bg-primary-md uppercase tracking-widest hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 min-w-[80px]')
                ->dispatch('edit', ['rowId' => $row->id]) // Dispatch the event when clicked
            // Dispatch the event when clicked
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
