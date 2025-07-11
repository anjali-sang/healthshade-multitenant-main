<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentProducts extends Model
{
    protected $fillable = [
        'shipment_id',
        'product_id',
        'batch_id',
        'quantity',
        'shipment_unit_id',
        'net_unit_price',
        'total_price',
        'user_id',
    ];

    // A shipment product belongs to a shipment
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(BatchInventory::class, 'batch_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'shipment_unit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
