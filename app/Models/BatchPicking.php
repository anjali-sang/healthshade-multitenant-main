<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchPicking extends Model
{
    protected $fillable = [
        'picking_number',
        'location_id',
        'batch_id',
        'organization_id',
        'user_id',
        'product_id',
        'picking_quantity',
        'picking_unit',
        'net_unit_price', 
        'total_amount',
        'chart_number'
    ];

    public function batchInventory()
    {
        return $this->belongsTo(BatchInventory::class, 'batch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function generatePickingNumber()
    {
        $year = date('Y');
        $lastOrder = self::where('picking_number', 'LIKE', "BT-{$year}-%")
            ->latest('id')
            ->first();
        if (!$lastOrder) {
            $nextNumber = '000001';
        } else {
            $lastNumber = (int) substr($lastOrder->picking_number, -6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return "BT-{$year}-{$nextNumber}";
    }
     public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
