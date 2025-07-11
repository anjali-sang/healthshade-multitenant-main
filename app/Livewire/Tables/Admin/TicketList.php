<?php

namespace App\Livewire\Tables\Admin;

use App\Models\Ticket;
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


final class TicketList extends PowerGridComponent
{
    public string $tableName = 'ticket-list-l4gdla-table';
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

            //     ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Ticket::query()
            ->leftJoin('organizations', 'tickets.organization_id', '=', 'organizations.id')
            ->leftJoin('users', 'tickets.creator', '=', 'users.id')
            ->select('tickets.*', 'organizations.id as organization_id', 'users.name as creator_name')
            ->orderBy('created_at', 'desc'); // Ensure organization_id is selected
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('id')
            // ->add('image')
            ->add('creator_name')
            ->add('module')
            ->add('description')
            ->add('status', function ($row) {
                $status = $row->status;
                if ($status == 'Closed') {
                    return '<span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 py-0.5 px-1.5 text-xs rounded-full border-2 border-green-800">Closed</span>';
                } elseif ($status == 'Open') {
                    return '<span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 py-0.5 px-1.5 text-xs  rounded-full border-2 border-red-800">Open</span>';
                }

                return $status;
            })
            ->add('priority')
            ->add('closed_at')
            ->add('organization.name')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            // Column::make('Id', 'id'),
//             Column::make('Image', 'image')
//                 ->sortable()
//                 ->searchable(),

            Column::make('Creator', 'creator_name')
                ->sortable()
                ->searchable(),

            Column::make('Module', 'module')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Priority', 'priority')
                ->sortable()
                ->searchable(),

            Column::make('Organization', 'organization.name')
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
            Filter::datetimepicker('closed_at'),
            Filter::datetimepicker('tickets.created_at'),
        ];
    }

    public function actions(Ticket $row): array
    {
        return [
            Button::add('view')
                ->slot('
                View ticket
            ')
                ->id( $row->id)
                ->class('inline-flex items-center px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white dark:bg-primary-md uppercase tracking-widest hover:bg-primary-dk dark:hover:bg-primary-dk focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150')
                ->dispatch('ticket-admin-modal', [
                    'ticketId' => $row->id,
                ])
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
