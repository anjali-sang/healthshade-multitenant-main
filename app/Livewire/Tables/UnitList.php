<?php

namespace App\Livewire\Tables;

use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UnitList extends PowerGridComponent
{
    public string $tableName = 'unit-list-pmsemm-table';


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

            // PowerGrid::header()
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
        return Unit::query()->where('is_deleted', false);
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('unit_name')
            ->add('unit_code')
            ->add('is_active', fn($item) => $item->is_active ? '<div class="text-green-400">Active</div>' : '<div class="text-red-400">Inactive</div>')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
            ->hidden(),
            Column::make('Unit name', 'unit_name')
                ->sortable()
                ->searchable(),

            Column::make('Unit code', 'unit_code')
                ->sortable()
                ->searchable(),

            Column::make('Is active', 'is_active')
                ->sortable()
                ->searchable(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('unit_name')->placeholder('Unit Name')->operators(['contains']),
            Filter::inputText('unit_code')->placeholder('Unit Code')->operators(['contains']),
            Filter::boolean('is_active')->label('Active', 'Inactive'),
        ];
    }

    // #[\Livewire\Attributes\On('edit-unit')]
    // public function edit($rowId): void
    // {
    //     $this->js('alert('.$rowId.')');
    // }

    public function actions(Unit $row): array
{
    return [
        Button::add('edit')
        ->slot('
            <span class="w-24 flex justify-center items-center relative" x-data="{ loading: false }"
                  x-on:click="loading = true; $dispatch(\'edit-unit\', { rowId: ' . $row->id . ' }); setTimeout(() => loading = false, 1000)">

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
        ->class('inline-flex items-center justify-center w-24 px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dk dark:hover:bg-primary-dk focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150')
        ->dispatch('edit-unit', ['rowId' => $row->id])

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
