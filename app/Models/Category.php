<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['category_name', 'category_description', 'organization_id', 'is_active'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'category_id', 'id');
    }
}
