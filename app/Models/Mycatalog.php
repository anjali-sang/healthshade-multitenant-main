<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mycatalog extends Model
{
    protected $table = 'mycatalogs';

    protected $fillable = [
        'organization_id',
        'product_id',
        'category_id',
        'product_cost',
        'product_price',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
