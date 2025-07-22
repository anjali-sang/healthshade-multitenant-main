<?php

namespace App\Livewire\Tables\User;

use App\Models\Organization;
use App\Models\PurchaseOrder;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class PurchaseList extends PowerGridComponent
{
    public string $tableName = 'purchase-list-w6kthw-table';
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
            //     ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('purchase_oder_number','Action'),
        ];
    }

    public function datasource(): Builder
    {
        return PurchaseOrder::with(['purchaseSupplier:id,supplier_name,supplier_email', 'purchaseLocation:id,name'])
            ->whereHas('purchaseSupplier')
            ->where('purchase_orders.status', '!=', 'completed')
            ->orderBy('id', 'desc');
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
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('purchase_oder_number')
            ->add('supplier_id')
            ->add('supplier_name', fn($purchaseOrder) => e($purchaseOrder?->purchaseSupplier?->supplier_name))
            ->add('organization_id')
            ->add('location_name', fn($purchaseOrder) => e($purchaseOrder->purchaseLocation->name))
            ->add('organization_name', fn($purchaseOrder) => e($purchaseOrder->organization->name))
            ->add('bill_to_location_id')
            ->add('ship_to_location_id')
            ->add('status', function ($row) {
                $status = $row->status;

                // Apply Tailwind classes based on the status
                if ($status == 'completed') {
                    return '<span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 py-0.5 px-1.5 text-xs rounded-full border-2 border-green-800">Completed</span>';
                } elseif ($status == 'ordered') {
                    return '<span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 py-0.5 px-1.5 text-xs  rounded-full border-2 border-blue-800">Ordered</span>';
                } elseif ($status == 'pending') {
                    return '<span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 py-0.5 px-1.5 text-xs rounded-full border-2 border-yellow-800">Pending</span>';
                } elseif ($status == 'partial') {
                    return '<span class="bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300 py-0.5 px-1.5 text-xs rounded-full border-2 border-orange-800">Partial</span>';
                }

                return $status; // default in case status doesn't match
            })

            ->add('total')
            ->add('created_at')
            ->add('updated_at')
            ->add('created_by', fn($purchaseOrder) => e(optional($purchaseOrder->createdUser)->name ?? 'N/A'))
            ->add('updated_by')
            ->add('invoice')
            ->add('note')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Purchase order', 'purchase_oder_number')
                ->sortable()
                ->searchable(),
            Column::make('Organization', 'organization_name'),
            Column::make('Supplier', 'supplier_name'),
            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),
            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('purchase_oder_number')->placeholder('Purchase Order No')->operators(['contains']),
            Filter::select('supplier_name', 'supplier_id')
                ->dataSource(Supplier::all())
                ->optionLabel('supplier_name')
                ->optionValue('id'),
            Filter::select('organization_name', 'organization_id')
                ->dataSource(Organization::where('is_active',true)->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(PurchaseOrder $row): array
    {
        return [
            Button::add('view')
                ->slot('Details')
                ->id()
                ->class('text-primary-dk font-semibold')
                ->dispatch('rowClicked', ['id' => $row->id]),
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
