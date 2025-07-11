<?php

namespace App\Livewire\Tables\Organization;

use App\Models\Brand;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ManufacturerList extends PowerGridComponent
{
    public string $tableName = 'manufacturer-list-uxq1na-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ,
            PowerGrid::footer()
                ->showPerPage(50)
                ->showRecordCount(),
            PowerGrid::responsive()->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        return Brand::query()->where('organization_id',auth()->user()->organization_id)
        ->where('brand_is_active', true);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('brand_name')
            ->add('brand_image',function ($item) {
                return '<img class="w-10 h-10 rounded-md" src="' . asset('storage/' . $item->brand_image) . '">';
            })
            ->add('brand_is_active')
            ->add('organization_id')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('image', 'brand_image')
            ->sortable()
            ->searchable(),

            Column::make('Manufacturer', 'brand_name')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('brand_name')
                ->placeholder('Manufacturer')
                ->operators([
                    'contains',
                ]),
        ];
    }

    public function actions(Brand $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('bg-primary-md py-2 px-3 rounded text-white')
                ->dispatch('edit-brand', ['rowId' => $row->id])
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
