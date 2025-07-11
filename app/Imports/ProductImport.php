<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public $current = 0;
    private $userId;
    private $supplierId;
    private $skippedProducts = [];

    public function __construct($supplierId = null)
    {
        $this->userId = auth()->user()->id;
        $this->supplierId = $supplierId;
    }

    public function model(array $row)
    {
        $this->current++;

        // Skip the header row
        if ($this->current == 0) {
            return null;
        }
        $productCode = $row['product_code'] ?? null;
        $manufactureCode = $row['manufacture_code'] ?? null;
        $cost = $row['cost'] ?? null;
        $price = $row['price'] ?? null;
        $price = $price != null ? $price : $cost;
        
        if ($cost == null) {
            Log::info("Skipping product: Product code '$productCode' cost missing");
            $this->skippedProducts[] = [
                'product_code' => $productCode,
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'Product Cost not correct'
            ];
            return null;
        }

        if (auth()->user()->role_id == 1) {
            $organization_code = $row['organization_code'] ?? null;
            // if Org code is present
            if (empty($organization_code)) {
                $this->skippedProducts[] = [
                    'product_code' => $productCode ?? 'N/A',
                    'product_name' => $row['product_name'] ?? 'N/A',
                    'issue' => 'No organization code'
                ];
                return null;
            }
            //if Org is invalid
            if (!Organization::where('organization_code', $organization_code)->exists()) {
                $this->skippedProducts[] = [
                    'product_code' => $productCode ?? 'N/A',
                    'product_name' => $row['product_name'] ?? 'N/A',
                    'issue' => 'Invalid organization code'
                ];
                return null;
            }
            $organization = Organization::where('organization_code', $organization_code)->first();
            $category = null;
            if ($row['category'] != null) {
                $category = Category::firstOrCreate(
                    [
                        'category_name' => $row['category'],
                        'organization_id' => $organization->id
                    ],
                    [
                        'category_description' => 'Description is pending'
                    ]
                );
            }
        } else {
            //if Org is invalid
            if (!Organization::where('id', auth()->user()->organization_id)->exists()) {
                Log::info('Skipping product: Invalid organization code', $row);
                $this->skippedProducts[] = [
                    'product_code' => $productCode ?? 'N/A',
                    'product_name' => $row['product_name'] ?? 'N/A',
                    'issue' => 'Invalid organization code'
                ];
                return null;
            }

            $organization = Organization::where('id', auth()->user()->organization_id)->first();

            $category = null;
            if ($row['category'] != null) {
                $category = Category::firstOrCreate(
                    [
                        'category_name' => $row['category'],
                        'organization_id' => auth()->user()->organization_id
                    ],
                    [
                        'category_description' => 'Description is pending'
                    ]
                );
            }
        }

        // Check if product_code already exists
        if ($productCode && Product::where('product_code', $productCode)->where('organization_id', $organization->id)->exists()) {
            $this->skippedProducts[] = [
                'product_code' => $productCode,
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'Product code already exists'
            ];
            return null;
        }
        // Check if manufacture_code already exists
        if ($manufactureCode && Product::where('manufacture_code', $manufactureCode)->exists()) {
            Log::info("Skipping product: Manufacture code '$manufactureCode' already exists");
            $this->skippedProducts[] = [
                'product_code' => $productCode ?? 'N/A',
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'Manufacture code already exists'
            ];
            return null;
        }
        // Check if base_unit_code is missing
        if (empty($row['base_unit_code'])) {
            Log::info('Skipping product: No base unit code', $row);
            $this->skippedProducts[] = [
                'product_code' => $productCode ?? 'N/A',
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'No base unit code'
            ];
            return null;
        }

        $unit = Unit::where('unit_code', $row['base_unit_code'])->first();

        // Check if base unit is not found
        if (!$unit) {
            $this->skippedProducts[] = [
                'product_code' => $productCode ?? 'N/A',
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'Base unit not found'
            ];
            return null;
        }
        DB::beginTransaction();
        try {
            $product = Product::create([
                'product_name' => $row['product_name'] ?? null,
                'product_code' => $productCode,
                'product_supplier_id' => $this->supplierId,
                'product_description' => $row['product_description'] ?? null,
                'manufacture_code' => $manufactureCode,
                'created_by' => $this->userId,
                'updated_by' => $this->userId,
                'cost'=>$cost,
                'price'=>$price,
                'organization_id' => $organization->id,
                'category_id' => optional($category)->id
            ]);

            if (!$product) {
                throw new \Exception('Product creation failed');
            }

            ProductUnit::create([
                'product_id' => $product->id,
                'unit_id' => $unit->id,
                'is_base_unit' => 1,
                'operator' => 'multiply',
            ]);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return null;
        }

    }

    // Function to download skipped products as a CSV file
    public function downloadSkippedCsv()
    {
        if (empty($this->skippedProducts)) {
            return response()->json(['message' => 'No skipped products to download'], 400);
        }

        $headers = ['product_code', 'product_name', 'issue'];
        $csv = implode(',', $headers) . "\n";

        foreach ($this->skippedProducts as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'skipped_products.csv');
    }

    public function getskippedProducts()
    {
        return $this->skippedProducts;
    }
}
