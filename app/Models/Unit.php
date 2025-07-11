<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_name',
        'unit_code',
        'is_active',
        'is_deleted'
    ];
    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class, 'unit_id');
    }

}
