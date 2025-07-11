<?php

namespace App\Livewire\Tables\Reports;

use App\Models\Organization;
use App\Models\PurchaseOrder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Models\Location;
use App\Models\Supplier;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Livewire\WithPagination;

final class PurchaseReportList extends PowerGridComponent
{
    public string $tableName = 'purchase-report-list-slevws-table';

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

    public function datasource(): Builder
    {
        $query = PurchaseOrder::with(['purchaseSupplier', 'purchaseLocation', 'organization'])
            ->select(
                'purchase_orders.*',
                'purchase_orders.created_at as purchase_order_created_at',
            )
            ->where('purchase_orders.status', 'Completed')
            ->orderBy('purchase_orders.id', 'desc');
        if (auth()->user()->role_id != 1) {
            $query->where('purchase_orders.organization_id', auth()->user()->organization_id);
        }
        return $query;
    }


    public function relationSearch(): array
    {
        return [
            'purchaseSupplier' => [
                'supplier_name',
            ],
            'purchaseLocation' => [
                'name',
            ],
            'organization' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('purchase_oder_number', fn($purchaseOrder) => '<span class="font-semibold">' . e($purchaseOrder->purchase_oder_number) . '</span>')
            ->add('supplier_id')
            ->add('name', fn($purchaseOrder) => e($purchaseOrder->organization->name))
            ->add('supplier_name', fn($purchaseOrder) => e($purchaseOrder->purchaseSupplier?->supplier_name))
            ->add('location_name', fn($purchaseOrder) => e($purchaseOrder->purchaseLocation->name))
            ->add('organization_id')
            ->add('location_id')
            ->add('product_name')
            ->add('quantity')
            ->add('bill_to_location_id')
            ->add('ship_to_location_id')
            ->add('status', fn($purchaseOrder) => '<span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 py-0.5 px-1.5 text-xs rounded-full border-2 border-green-800 font-semibold">' . $purchaseOrder->status . '</span>')
            ->add('total', function ($model) {
                return session('currency', '$') . ' ' . number_format($model->total ?? 0, 2);
            })
            ->add('updated_at')
            ->add('purchase_orders.created_at', function ($model) {
                return date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at));
            })
            ->add('updated_by')
            ->add('invoice')
            ->add('note');
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Created at', 'purchase_orders.created_at')
                ->sortable()
                ->searchable()
                ->bodyAttribute('class', 'w-12 text-xs '),

            Column::make('Purchase Order', 'purchase_oder_number')
                ->sortable()
                ->searchable()
                ->bodyAttribute('class', 'text-xs font-semibold !important'),

            Column::make('Supplier', 'supplier_name')
                ->bodyAttribute('class', 'w-16 text-xs '),

            Column::make('Location', 'location_name')
                ->bodyAttribute('class', 'w-16 text-xs '),

            Column::make('Product', 'product_name')
                ->hidden(),

            Column::make('Quantity', 'quantity')
                ->bodyAttribute('class', 'w-16 text-xs ')
                ->hidden(),

            Column::make('Status', 'status')
                ->searchable()
                ->sortable()
                ->bodyAttribute('class', 'w-12 text-xs'),

            Column::make('Total', 'total')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
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
            Filter::inputText('purchase_oder_number')->placeholder('Purchase Order No')->operators([
                'contains',
                'contains_not',
                'is',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::inputText('product_name')->placeholder('Product')->operators([
                'contains',
                'contains_not',
                'is',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::inputText('quantity')->placeholder('Quantity')->operators([
                'contains',
                'contains_not',
                'is',
                'is_not',
                'starts_with',
                'ends_with',
            ]),
            Filter::inputText('status')->placeholder('Status')->operators(['contains']),
            Filter::inputText('product_code')->placeholder('Code')->operators(['contains']),
            Filter::select('supplier_name', 'supplier_id')
                ->dataSource(Supplier::all())
                ->optionLabel('supplier_name')
                ->optionValue('id'),
            Filter::select('name', 'organization_id')
                ->dataSource(Organization::all())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('location_name', 'location_id')
                ->dataSource(Location::where('org_id', auth()->user()->organization_id)->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::number('total', 'total')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),
            Filter::number('sub_total', 'sub_total')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),
            Filter::datetimepicker('purchase_orders.created_at'),
        ];
    }

    #[On('showViewModal')]
    public function showViewModal($rowId)
    {
        $this->dispatchBrowserEvent('showViewModal', ['rowId' => $rowId]);
    }


    public function actions(PurchaseOrder $row): array
    {
        return [
            Button::add('view')
                ->slot('
                    View
                ')
                ->id()
                ->class('inline-flex items-center justify-center px-4 py-2 min-w-[80px] text-xs font-semibold text-white uppercase tracking-widest rounded-md border border-transparent bg-primary-md hover:bg-primary-lt focus:bg-primary-dk active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 transition duration-150 ease-in-out')
                ->dispatch('purchase-receive-view-modal', [
                    'rowId' => $row->id,
                ]),
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
