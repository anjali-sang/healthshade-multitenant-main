<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickingDetailsModel extends Model
{
    protected $table = "picking_details";

    protected $fillable = [
        "picking_id",
        "product_id",
        "picking_quantity",
        "picking_unit",
        "net_unit_price",
        "sub_total",
    ];

    /**
     * Get the product associated with this picking detail.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the picking unit associated with this picking detail.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'picking_unit');
    }
}
