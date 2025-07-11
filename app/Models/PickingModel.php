<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickingModel extends Model
{
    protected $table = "pickings";

    protected $fillable = [
        "id",
        "picking_number",
        "location_id",
        "user_id",
        "total",
        "created_at",
        "organization_id"
    ];

    /**
     * Get the user who created the picking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the location associated with the picking.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function pickingDetails()
{
    return $this->hasMany(PickingDetailsModel::class, 'picking_id');
}

    /**
     * Get all picking items related to this picking.
     */
    public function pickingItems(): HasMany
    {
        return $this->hasMany(PickingDetailsModel::class, 'picking_id');
    }

    public static function generatePickingNumber()
    {
        $year = date('Y');
        $lastOrder = self::where('picking_number', 'LIKE', "QP-{$year}-%")
            ->latest('id')
            ->first();
        if (!$lastOrder) {
            $nextNumber = '000001';
        } else {
            $lastNumber = (int) substr($lastOrder->picking_number, -6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return "QP-{$year}-{$nextNumber}";
    }
}
