<?php

namespace App\Livewire\Tables\Organization;

use App\Models\Patient;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Models\AuditModel;
use App\Models\Location;
use App\Models\Organization;
use App\Models\PickingModel;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class PatientsList extends PowerGridComponent
{
    public string $tableName = 'patients-list-gx9aih-table';
    use WithExport;
    public bool $showFilters = true;
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
            PowerGrid::header(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('chartnumber', 'drug', 'formatted_profit', 'actions'),
        ];
    }

    public function datasource(): Builder
    {
        return Patient::query()->where('is_active', true)->where('organization_id', auth()->user()->organization_id)->orderBy('id', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('chartnumber')
            ->add('formatted_cost', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->our_cost, 2);
            })
            ->add('formatted_paid', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->paid, 2);
            })
            ->add('formatted_pt_copay', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->pt_copay, 2);
            })
            ->add('formatted_profit', function ($item) {
                $currency = session('currency', '$');
                return $currency . ' ' . number_format($item->profit, 2);
            })
            ->add('formatted_address', function ($model) {
                // Create a formatted address from multiple fields
                $address = $model->address;
                $parts = [];

                if (!empty($model->address))
                    $parts[] = $model->address;
                if (!empty($model->state))
                    $parts[] = $model->state;
                if (!empty($model->country))
                    $parts[] = $model->country;
                if (!empty($model->pin_code))
                    $parts[] = $model->pin_code;

                $locationString = implode(', ', $parts);

                return !empty($locationString) ? "$address<br>$locationString" : $address;
            })
            ->add('export_address', function ($model) {
                // Create a formatted address from multiple fields
                $address = $model->address;
                $parts = [];

                if (!empty($model->address))
                    $parts[] = $model->address;
                if (!empty($model->state))
                    $parts[] = $model->state;
                if (!empty($model->country))
                    $parts[] = $model->country;
                if (!empty($model->pin_code))
                    $parts[] = $model->pin_code;

                $locationString = implode(', ', $parts);

                return !empty($locationString) ? "$address $locationString" : $address;
            })
            ->add('is_active', function ($item) {
                return $item->is_active ? 'Active' : 'Inactive';
            })
            ->add('date_given', function ($model) {
                return $model->date_given
                    ? date(session('date_format', 'Y-m-d'), strtotime($model->date_given))
                    : null;
            })
            // ->add('created_at_formatted', function ($model) {
            //     if ($model->created_at) {
            //         // Format the date according to the session format
            //         $formattedDate = $model->created_at->format(session('date_format', 'Y-m-d'));

            //         // Get the human-readable "time ago" string
            //         $humanReadable = $model->created_at->diffForHumans();

            //         // Return the formatted date along with the human-readable time
            //         return  $humanReadable;
            //     } else {
            //         return null;
            //     }
            // })
            ->add('ins_type')
            ->add('provider')
            ->add('icd')
            ->add('account_number')
            ->add('drug')
            ->add('dose')
            ->add('frequency')
            ->add('location')
            ->add('pa_expires');
    }

    public function columns(): array
    {
        return [
            Column::make('Date', 'date_given')
                ->sortable()
                ->searchable(),

            Column::make('Chart Number', 'chartnumber')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),

            Column::make('Insurance Type', 'ins_type')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Provider', 'provider')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('ICD', 'icd')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Account Number', 'account_number')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;')
                ->hidden(),
            Column::make('Drug', 'drug')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Dose', 'dose')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Our Cost', 'formatted_cost')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('INS Paid', 'formatted_paid')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('PT Paid', 'formatted_pt_copay')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Profit', 'formatted_profit')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Frequency', 'frequency')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::make('Location', 'location')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;')
                ->hidden(),
            Column::make('PA Expires', 'pa_expires')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;')
                ->hidden(),
            Column::make('Address', 'formatted_address')
                ->sortable()
                ->searchable()
                ->visibleInExport(false)
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [

            Filter::datetimepicker('date_given'),

            Filter::inputText('chartnumber')
                ->placeholder('Chart number')
                ->operators([
                    'contains',
                ]),
            Filter::inputText('ins_type')
                ->placeholder('Ins Type')
                ->operators([
                    'contains',
                ]),
            Filter::inputText('provider')
                ->placeholder('Provider')
                ->operators([
                    'contains',
                ]),
            Filter::inputText('icd')
                ->placeholder('ICD')
                ->operators([
                    'contains',
                ]),
            // Filter::inputText('account_number')
            //     ->placeholder('Account Number')
            //     ->operators([
            //         'contains',
            //     ]),
            Filter::inputText('country')
                ->placeholder('Country')
                ->operators([
                    'contains',
                ]),
        ];
    }

    public function actions(Patient $row): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm')
                ->dispatch('editPatient', ['patientId' => $row->id])
                ->can(function (Patient $patient) {
                    $user = auth()->user();
                    return $user->role_id <= 2 || $user->role?->hasPermission('edit_patient');
                }),

            // Button::make('delete', 'Delete')
            //     ->class('bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm')
            //     ->dispatch('deletePatient', ['patientId' => $row->id])
            //     ->can(function (Patient $patient) {
            //         $user = auth()->user();
            //         return $user->role_id <= 2 || $user->role?->hasPermission('delete_patient');
            //     }),
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
