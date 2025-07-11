<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchInventory extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'batch_number',
        'expiry_date',
        'organization_id',
        'location_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
