<?php

namespace App\Livewire\Tables\Reports;

use App\Models\AuditModel;
use App\Models\Location;
use App\Models\Organization;
use App\Models\PickingModel;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;


final class AuditReportList extends PowerGridComponent
{
    public string $tableName = 'audit-report-list-zdy6gu-table';

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
            PowerGrid::responsive()
                ->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        $query =  AuditModel::query()
            ->with(['user', 'user.location','organization'])
            ->orderBy('created_at', 'desc');
        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }
        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'name',
            ],
            'user.location' => [
                'name',
            ],
            'organization' => [
                'name',
            ],
        ];
    }
    public function formatJsonValues($values): string
    {
        if (empty($values)) {
            return '';
        }
        $data = is_array($values) ? $values : json_decode($values, true);
        if (!is_array($data)) {
            return (string) $values;
        }
        $formatted = [];
        foreach ($data as $key => $value) {
            $formattedKey = ucwords(str_replace('_', ' ', $key));
            $value ? $value : $value = 'N/A';
            if ($key == 'product_id') {
                try {
                    $product = Product::find($value);
                    if ($product) {
                        $formatted[] = "<strong>Code:</strong> {$product->product_code}<br/><strong>Product:</strong> {$product->product_name}";
                    } else {
                        $formatted[] = "<strong>Product ID:</strong> {$value} (not found)";
                    }
                } catch (\Exception $e) {
                    $formatted[] = "<strong>Product ID:</strong> {$value}";
                }
            }
            if ($key == 'location_id') {
                try {
                    $location = Location::find($value);
                    if ($location) {
                        $formatted[] = "<strong>Location:</strong> {$location->name}";
                    } else {
                        $formatted[] = "<strong>Location ID:</strong> {$value} (not found)";
                    }
                } catch (\Exception $e) {
                    $formatted[] = "<strong>Location ID:</strong> {$value}";
                }
            }
            if ($key == 'user_id') {
                try {
                    $user = User::find($value);
                    if ($user) {
                        $formatted[] = "<strong>User:</strong> {$user->name}";
                    } else {
                        $formatted[] = "<strong>User ID:</strong> {$value} (not found)";
                    }
                } catch (\Exception $e) {
                    $formatted[] = "<strong>User ID:</strong> {$value}";
                }
            }
            // Add more special cases for other IDs if needed
            // else if ($key === 'some_other_id') { ... }
            else {
                $formatted[] = "<strong>{$formattedKey}:</strong> {$value}";
            }
        }

        return implode('<br>', $formatted);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user.name')
            ->add('user.location.name')
            ->add('name', fn($item) => e($item->organization->name))
            ->add('event')
            ->add('auditable_type')
            ->add('auditable_id')
            ->add('old_values', fn($model) => $this->formatJsonValues($model->old_values))
            ->add('new_values', fn($model) => $this->formatJsonValues($model->new_values))
            ->add('audits.created_at', function ($model) {
                return $model->created_at
                    ? date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at))
                    : null;
            });
    }
    // private function formatRelatedModelData($model)
    // {
    //     if ($model->auditable_type == 'Picking') {
    //         return PickingModel::find($model->auditable_id)->pluck('picking_number')->first();
    //     }
    //     if ($model->auditable_type == 'Purchase Order') {
    //         return PurchaseOrder::find($model->auditable_id)->pluck('purchase_oder_number')->first();
    //     }
    //     $className = class_basename(get_class($model));
    //     return "{$className} #{$model->id}";
    // }

    public function columns(): array
    {
        $columns = [
            Column::make('Created at', 'audits.created_at')
                ->sortable()
                ->searchable(),

            Column::make('User', 'user.name')
                ->sortable()
                ->searchable(),

            Column::make('Location', 'user.location.name')
                ->sortable()
                ->searchable()->hidden(),
            Column::make('Event', 'event')
                ->sortable()
                ->searchable(),

            Column::make('Module', 'auditable_type')
                ->sortable()
                ->searchable(),

            Column::make('Reference', 'auditable_id')
                ->sortable()
                ->searchable(),

            Column::make('Old values', 'old_values')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl ', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),

            Column::make('New values', 'new_values')
                ->sortable()
                ->searchable()
                ->headerAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;')
                ->bodyAttribute('max-w-xl', 'min-width: 0; white-space: normal !important;'),

            Column::action('Action')->hidden()
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
            Filter::datetimepicker('audits.created_at'),
            Filter::select('user.name', 'user_id')
                ->dataSource(User::where('organization_id', auth()->user()->organization_id)->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::inputText('auditable_id')->placeholder('Reference')->operators([
                'contains',
                'contains_not',
                'is',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::select('name', 'organization_id')
                ->dataSource(Organization::all())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::inputText('event')->placeholder('Event')->operators([
                'contains',
                'contains_not',
                'is',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::inputText('auditable_type')->placeholder('Module')->operators([
                'contains',
                'contains_not',
                'is',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(AuditModel $row): array
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
