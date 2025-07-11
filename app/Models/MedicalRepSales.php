<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class MedicalRepSales extends Model
// {
//     protected $fillable = [
//         'sales_number',
//         'medical_rep_id',
//         'org_id',
//         'receiver_org_id',
//         'location_id',
//         'items',
//         'total_qty',
//         'total_price',
//         'status',
//     ];
//     protected $table = 'medical_rep_sales'; 

//     public function medicalRep()
//     {
//         return $this->belongsTo(User::class, 'medical_rep_id');
//     }
//     public function organization()
//     {
//         return $this->belongsTo(Organization::class, 'org_id');
//     }
//     public function receiverOrganization()
//     {
//         return $this->belongsTo(Organization::class, 'receiver_org_id');
//     }
//     public function location()
//     {
//         return $this->belongsTo(Location::class, 'location_id');
//     }
//     public function saleItems()
//     {
//         return $this->hasMany(MedicalRepSalesProducts::class, 'sales_id');
//     }
// }
