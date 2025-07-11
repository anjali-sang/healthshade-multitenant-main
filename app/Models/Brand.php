<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = "brands";
    protected $fillable = [
        'brand_name',
        'brand_image',
        'brand_is_active',
        'organization_id'
    ];
}
