<?php

// namespace App\Livewire\Tables\Medicalrep;

// use App\Models\MedicalRepSales;
// use Illuminate\Support\Carbon;
// use Illuminate\Database\Eloquent\Builder;
// use PowerComponents\LivewirePowerGrid\Button;
// use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
// use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
// use PowerComponents\LivewirePowerGrid\PowerGridFields;
// use PowerComponents\LivewirePowerGrid\PowerGridComponent;
// use App\Models\Shipment;

// final class SalesList extends PowerGridComponent
// {
//     public string $tableName = 'sales-list-jklvkq-table';

//     public function setUp(): array
//     {
//         $this->showCheckBox();

//         return [
//             PowerGrid::header()
//                 ->showSearchInput(),
//             PowerGrid::footer()
//                 ->showPerPage()
//                 ->showRecordCount(50),
//         ];
//     }

//     public function datasource(): Builder
//     {
//         return MedicalRepSales::query()->with(['medicalRep', 'organization', 'location', 'receiverOrganization']);
//     }

//     public function relationSearch(): array
//     {
//         return [
//             'medicalRep' => [
//                 'name',
//             ],
//             'organization' => [
//                 'name',
//             ],
//             'location' => [
//                 'name',
//             ],
//             'receiverOrganization' => [
//                 'name',
//             ],
//         ];
//     }

//     public function fields(): PowerGridFields
//     {
        
//         return PowerGrid::fields()
//             ->add('id')
//             ->add('sales_number')
//             ->add('medical_rep', function ($model) {
//                 return $model->medicalRep->name;
//             })
//             ->add('receiver_org', function ($model) {
//                 return $model->receiverOrganization ? $model->receiverOrganization->name : 'N/A';
//             })
//             ->add('location', function ($model) {
//                 return $model->location ? $model->location->name : 'N/A';
//             })
//             ->add('total_qty')
//             ->add('total_price', function ($model) {
//                 $currency = session('currency', '$');
//                 return $currency . ' ' . number_format($model->total_price, 2);
//             })
//             ->add('status', function ($model) {
//                 $status = strtolower($model->status);
//                 $badgeColor = match ($status) {
//                     'pending' => 'bg-yellow-100 text-yellow-800',
//                     'completed' => 'bg-green-100 text-green-800',
//                     default => 'bg-gray-100 text-gray-800',
//                 };

//                 return '<span class="px-2 py-1 rounded-full text-xs font-semibold ' . $badgeColor . '">' . ucfirst($status) . '</span>';
//             })
//             ->add('created_at');
//     }


//     public function columns(): array
//     {
//         return [
//             Column::make('Sales', 'sales_number')
//                 ->sortable()
//                 ->searchable(),

//             Column::make('Organization', 'receiver_org')
//                 ->sortable()
//                 ->searchable(),

//             Column::make('Location', 'location')
//                 ->sortable()
//                 ->searchable(),

//             Column::make('Items', 'items')
//                 ->sortable()
//                 ->searchable(),

//             Column::make('Total qty', 'total_qty')
//                 ->sortable()
//                 ->searchable(),

//             Column::make('Total price', 'total_price')
//                 ->sortable()
//                 ->searchable(),

//             Column::make('Status', 'status')
//                 ->sortable()
//                 ->searchable(),

//             Column::action('Action')
//         ];
//     }

//     public function filters(): array
//     {
//         return [
//         ];
//     }

//     public function actions(MedicalRepSales $row): array
//     {
//         return [
//             Button::add('view')
//                 ->slot('View')
//                 ->id()
//                 ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
//                 ->dispatch('view', ['saleId' => $row->id])
//         ];
//     }

//     /*
//     public function actionRules($row): array
//     {
//        return [
//             // Hide button edit for ID 1
//             Rule::button('edit')
//                 ->when(fn($row) => $row->id === 1)
//                 ->hide(),
//         ];
//     }
//     */
// }
