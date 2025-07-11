<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'id',
        'supplier_name',
        'supplier_slug',
        'supplier_email',
        'supplier_phone',
        'supplier_address',
        'supplier_city',
        'supplier_state',
        'supplier_country',
        'supplier_zip',
        'supplier_vat',
        'created_by',
        'updated_by',
        'is_active',

    ];
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }
}
