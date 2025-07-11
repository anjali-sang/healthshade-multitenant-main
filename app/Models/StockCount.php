<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    use HasFactory;

    protected $table = 'stock_counts'; 

    protected $fillable = [
        'product_id',
        'location_id',
        'on_hand_quantity',
        'organization_id',
        'alert_quantity',
        'par_quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
