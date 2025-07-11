<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

// class MedrepShipment extends Model
// {
//     protected $fillable = [
//         'sale_id',
//         'tracking_number',
//         'carrier',
//         'service_type',
//         'label_url',
//         'cost',
//         'status',
//         'shipped_at',
//         'delivered_at',
//         'notes'
//     ];

//     protected $casts = [
//         'shipped_at' => 'datetime',
//         'delivered_at' => 'datetime',
//         'cost' => 'decimal:2'
//     ];

//     /**
//      * Get the sale that owns the shipment
//      */
//     public function sale(): BelongsTo
//     {
//         return $this->belongsTo(MedicalRepSales::class, 'sale_id');
//     }

//     /**
//      * Get status color for display
//      */
//     public function getStatusColorAttribute(): string
//     {
//         return match($this->status) {
//             'pending' => 'yellow',
//             'shipped' => 'blue',
//             'in_transit' => 'purple',
//             'delivered' => 'green',
//             'failed' => 'red',
//             default => 'gray'
//         };
//     }

//     /**
//      * Get formatted cost
//      */
//     public function getFormattedCostAttribute(): string
//     {
//         return '$' . number_format($this->cost, 2);
//     }
// }
