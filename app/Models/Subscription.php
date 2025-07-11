<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'max_users',
        'max_locations',
        'created_at',
        'is_deleted',
        'is_active',
        'updated_at',
        'duration',
        'created_by',
        'updated_by',
        'stripe_price_id',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'subscription_id');
    }
}
