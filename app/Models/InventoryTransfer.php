<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;


class InventoryTransfer extends Model
{
    protected $fillable = [
        'reference_number',
        'product_id',
        'quantity',
        'unit_id',
        'from_location_id',
        'to_location_id',
        'supplier_id',
        'organization_id',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id', 'id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public static function generateTransferNumber()
    {
        $year = date('Y');
        $lastOrder = self::where('reference_number', 'LIKE', "TRA-{$year}-%")
            ->latest('id')
            ->first();
        if (!$lastOrder) {
            $nextNumber = '000001';
        } else {
            $lastNumber = (int) substr($lastOrder->reference_number, -6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return "TRA-{$year}-{$nextNumber}";
    }
}
