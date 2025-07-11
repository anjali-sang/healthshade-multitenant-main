<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PurchaseOrder extends Model
{
    protected $table = "purchase_orders";
    protected $fillable = [
        'purchase_oder_number',
        'merge_id',
        'supplier_id',
        'organization_id',
        'location_id',
        'bill_to_location_id',
        'ship_to_location_id',
        'status',
        'total',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'invoice',
        'note',
        'is_order_placed',
        'bill_to_number',
        'ship_to_number',
        'invoice_path',
        'acknowledgment_path',
        'invoice_uploaded_at',
        'status',
        'tracking_link',

    ];
    protected $casts = [
        'invoice_uploaded_at' => 'datetime',
    ];

    // Helper methods to get file URLs
    public function getInvoiceUrlAttribute()
    {
        return $this->invoice_path ? Storage::url($this->invoice_path) : null;
    }

    public function getAcknowledmentUrlAttribute()
    {
        return $this->acknowledgment_path ? Storage::url($this->acknowledgment_path) : null;
    }

    // Helper methods to check if files exist
    public function hasInvoice()
    {
        return !is_null($this->invoice_path) && Storage::exists($this->invoice_path);
    }


    public function hasAcknowledgment()
    {
        return !is_null($this->acknowledgment_path) && Storage::exists($this->acknowledgment_path);
    }
    public function purchasedProducts()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }
    public function purchaseSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function purchaseLocation()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function shippingLocation()
    {
        return $this->belongsTo(Location::class, 'ship_to_location_id');
    }
    public static function generatePurchaseOrderNumber()
    {
        $year = date('Y');
        $lastOrder = self::where('purchase_oder_number', 'LIKE', "PO-{$year}-%")
            ->latest('id')
            ->first();
        if (!$lastOrder) {
            $nextNumber = '000001';
        } else {
            $lastNumber = (int) substr($lastOrder->purchase_oder_number, -6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return "PO-{$year}-{$nextNumber}";
    }
    public static function generateMergeId()
    {
        $year = date('Y');

        $lastMergeOrder = self::where('merge_id', 'LIKE', "MR-{$year}-%")
            ->latest('id')
            ->first();

        if (!$lastMergeOrder) {
            $nextNumber = '000001';
        } else {
            $lastNumber = (int) substr($lastMergeOrder->merge_id, -6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return "MR-{$year}-{$nextNumber}";
    }
    public function billingLocation()
    {
        return $this->belongsTo(Location::class, 'bill_to_location_id');
    }

}
