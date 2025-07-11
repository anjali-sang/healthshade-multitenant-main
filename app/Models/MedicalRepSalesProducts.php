<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class MedicalRepSalesProducts extends Model
// {
//     protected $fillable = [
//         'sales_id',
//         'product_id',
//         'product_name',
//         'product_code',
//         'unit_id',
//         'quantity',
//         'price',
//         'total',
//     ];

//     protected $table = 'medical_rep_sales_products';

//     public function sales()
//     {
//         return $this->belongsTo(MedicalRepSales::class, 'sales_id');
//     }
//     public function product()
//     {
//         return $this->belongsTo(Product::class, 'product_id');
//     }
//     public function unit()
//     {
//         return $this->belongsTo(Unit::class, 'unit_id');
//     }
// }
