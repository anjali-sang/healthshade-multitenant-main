<?php

namespace App\Livewire\Tables\Admin\Users;

use App\Models\PotentialClient;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class PotentialUser extends PowerGridComponent
{
    public string $tableName = 'potential-user-wozssd-table';
    public bool $showFilters = false;

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
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return PotentialClient::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('email')
            ->add('otp')
            ->add('otp_expires_at_formatted', fn(PotentialClient $model) => Carbon::parse($model->otp_expires_at)->format('d/m/Y H:i:s'))
            ->add('otp_verified', function ($item) {
                if ($item->otp_verified == 1) {
                    return '<div class="text-green-500 text-xs">Verified</div>';
                } else if ($item->otp_verified == 0) {
                    return '<div class="text-red-500 text-xs">Not Verifiesd</div>';
                }
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Verified', 'otp_verified')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')->hidden()
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datetimepicker('otp_expires_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(PotentialClient $row): array
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
