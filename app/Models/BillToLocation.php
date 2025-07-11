<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillToLocation extends Model
{
    protected $table = "bill_to_locations";
    protected $fillable = [
        'id',
        'organization_id',
        'location_id',
        'supplier_id',
        'bill_to',
        'created_by',
        'updated_by',
        'is_default',
    ];
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function createdUser()
    {
        return $this->belongsTo(Supplier::class, 'created_by');
    }
    public function updateddUser()
    {
        return $this->belongsTo(Supplier::class, 'updated_by');
    }

}
