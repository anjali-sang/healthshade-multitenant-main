<?php

namespace App\Livewire\Tables\Reports;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProductReport extends PowerGridComponent
{
    public string $tableName = 'product-report-zkls7p-table';

    public $selectedLocation = '';
    public $startDate = '';
    public $endDate = '';

    public $organization = '';

    protected $listeners = [
        'productLocationChanged' => 'updateFilters'
    ];

    public function updateFilters($location, $start_date, $end_date, $organization)
    {
        $this->selectedLocation = $location;
        $this->startDate = $start_date;
        $this->endDate = $end_date;
        if (auth()->user()->role_id == 1) {
            $this->organization = $organization;
        } else {
            $this->organization = auth()->user()->organization_id;
        }
        $this->validateDateRange();
        $this->resetPage();
    }

    public function validateDateRange()
    {
        if ($this->startDate && $this->endDate) {
            if ($this->endDate < $this->startDate) {
                [$this->startDate, $this->endDate] = [$this->endDate, $this->startDate];
            }
        }
    }


    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns(),
        ];
    }

    public function datasource(): Builder
    {
        $query = Product::query()->where('is_active', true);
        if (auth()->user()->role_id == 1 && $this->organization) {
            $query->where('organization_id', $this->organization);
        } else if ($this->organization) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        $query->withSum([
            'purchaseOrderDetails as total_purchase_quantity' => function ($q) {
                $q->whereHas('purchaseOrder', function ($query) {
                    if ($this->selectedLocation) {
                        $query->where('location_id', $this->selectedLocation);
                    }

                    if ($this->startDate && $this->endDate) {
                        $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    } elseif ($this->startDate) {
                        $query->whereDate('created_at', '>=', $this->startDate);
                    } elseif ($this->endDate) {
                        $query->whereDate('created_at', '<=', $this->endDate);
                    }
                });
            }
        ], 'quantity')
            ->withSum([
                'purchaseOrderDetails as total_purchase_amount' => function ($q) {
                    $q->whereHas('purchaseOrder', function ($query) {
                        if ($this->selectedLocation) {
                            $query->where('location_id', $this->selectedLocation);
                        }

                        if ($this->startDate && $this->endDate) {
                            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                        } elseif ($this->startDate) {
                            $query->whereDate('created_at', '>=', $this->startDate);
                        } elseif ($this->endDate) {
                            $query->whereDate('created_at', '<=', $this->endDate);
                        }
                    });
                }
            ], 'sub_total');

        return $query;
    }



    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('image', function ($item) {
                $images = json_decode($item->image, true);
                $imagePath = is_array($images) && !empty($images) ? $images[0] : $item->image;
                return '<img class="w-10 h-10 rounded-md" src="' . asset('storage/' . $imagePath) . '">';
            })
            ->add('product_name')
            ->add('total_purchase_amount', function ($model) {
                return session('currency', '$') . ' ' . number_format($model->total_purchase_quantity ?? 0, 2);
            })
            ->add('total_purchase_quantity', function ($item) {
                return $item->total_purchase_quantity ?? 0;
            })
            ->add('product_code')
            ->add('product_supplier_id')
            ->add('product_description')
            ->add('has_expiry_date')
            ->add('created_by')
            ->add('updated_by')
            ->add('manufacture_code')
            ->add('organization_id')
            ->add('category_id')
            ->add('cost')
            ->add('price')
            ->add('is_active')
            ->add('created_at',function ($model) {
                return $model->created_at
                    ? date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($model->created_at))
                    : null;
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')->hidden(),
            Column::make('Image', 'image')
                ->sortable()
                ->searchable(),

            Column::make('Product code', 'product_code')
                ->sortable()
                ->searchable(),

            Column::make('Product name', 'product_name')
                ->sortable()
                ->searchable()
                ->headerAttribute('', ' white-space: normal !important;')
                ->bodyAttribute('', ' white-space: normal !important;'),

            Column::make('Purchased Quantity', 'total_purchase_quantity')
                ->sortable(),

            Column::make('Purchased Amount', 'total_purchase_amount')
                ->sortable(),

            Column::action('Action')->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Product $row): array
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
