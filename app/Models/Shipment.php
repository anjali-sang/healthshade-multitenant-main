<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_number',
        'user_id',
        'customer_id',
        'location_id',
        'total_quantity',
        'total_price',
        'grand_total',
    ];

    // A shipment has many shipment products
    public function shipmentProducts(): HasMany
    {
        return $this->hasMany(ShipmentProducts::class);
    }

    // Optional relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
