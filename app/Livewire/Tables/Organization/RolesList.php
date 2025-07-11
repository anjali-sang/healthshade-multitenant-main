<?php

namespace App\Livewire\Tables\Organization;



use App\Models\Roles;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Mycatalog;
use App\Models\Product;
use App\Models\Supplier;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Rule;

final class RolesList extends PowerGridComponent
{
    public string $tableName = 'roles-list-fgzyjv-table';
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
            ->queues(2)
            ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        return Roles::query()->where('is_active',true)->where('organization_id',auth()->user()->organization_id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('role_name')
            ->add('role_description')
            ->add('is_active')
            ->add('organization_id')
            ->add('created_at_formatted', function ($model) {
                if ($model->created_at) {
                    // Format the date according to the session format
                    $formattedDate = $model->created_at->format(session('date_format', 'Y-m-d'));

                    // Get the human-readable "time ago" string
                    $humanReadable = $model->created_at->diffForHumans();

                    // Return the formatted date along with the human-readable time
                    return $formattedDate . ' (' . $humanReadable . ')';
                } else {
                    return null;
                }
            })
            ->add('created_at', function ($model) {
                return $model->created_at;
            });


    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->hidden(),
            Column::make('Role', 'role_name')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'role_description')
                ->sortable()
                ->searchable(),

            Column::make('Is active', 'is_active')
                ->sortable()
                ->searchable()->hidden(),

            Column::make('Created At', 'created_at_formatted'), 

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

   

    public function actions(Roles $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('roles-edit', ['rowId' => $row->id])
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
