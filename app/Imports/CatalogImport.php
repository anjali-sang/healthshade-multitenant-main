<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Location;
use App\Models\Mycatalog;
use App\Models\StockCount;
use Hamcrest\Type\IsNumeric;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CatalogImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $current = 0;
    private $userId;
    private $skippedProducts = [];

    public $locations = [];
    public function __construct()
    {
        $this->userId = auth()->user()->id;
        $this->locations = Location::where('org_id',auth()->user()->organization_id)->where('is_active',true)->get();
    }
    public function model(array $row)
    {
        $this->current++;

        // Skip the header row
        if ($this->current == 0) {
            return null;
        }

        $productCode = $row['product_code'];

        if ($row['product_cost'] === null || !is_numeric($row['product_cost'])) {
            Log::info("Skipping product: Invalid Cost for Product code '$productCode'");
            $this->skippedProducts[] = [
                'product_code' => $productCode,
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'Invalid Cost'
            ];
            return null;
        }
        

        // Check if product_code already exists
        if ($productCode && !Product::where('product_code', $productCode)->exists()) {
            Log::info("Skipping product:Invalid Product code '$productCode'");
            $this->skippedProducts[] = [
                'product_code' => $productCode,
                'product_name' => $row['product_name'] ?? 'N/A',
                'issue' => 'Invalid Product code'
            ];
            return null;
        }
        $category = null;
        $product = Product::where('product_code', $productCode)->first();
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

        DB::beginTransaction();
        try {
            $catalog = Mycatalog::firstOrNew([
                'organization_id' => auth()->user()->organization_id,
                'product_id' => $product->id,
            ]);
        
            $catalog->category_id = optional($category)->id;
            $catalog->product_cost = $row['product_cost'];
            $catalog->product_price = $row['product_cost'];
            $catalog->save();

            if (!$product) {
                throw new \Exception('Product creation failed');
            }

            foreach ($this->locations as $location) {
    
                $stockCount = StockCount::firstOrNew([
                    'product_id' => $product->id,
                    'location_id' => $location->id,
                    'organization_id' => auth()->user()->organization_id,
                ]);
    
                $stockCount->alert_quantity = $row['alert_quantity'] ?? 3;
                $stockCount->par_quantity = $row['par_quantity'] ?? 10;
                
                $stockCount->save();
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return null;
        }
    }
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
