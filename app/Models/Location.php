<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'id',
        'name',
        'nick_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'country',
        'pin',
        'org_id',
        'is_active',
        'is_deleted',
        'created_by',
        'created_at',
        'location_id',
        'is_default'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'location_id');
    }

    protected static function boot()
{
    parent::boot();

    static::created(function ($location) {
        $org_id = auth()->user()->organization_id;
        $products = Product::all();

        foreach ($products as $product) {
            StockCount::firstOrCreate([
                'organization_id' => $org_id,
                'product_id' => $product->id,
                'location_id' => $location->id,
            ], [
                'on_hand_quantity' => 0,
            ]);
        }
    });
}
}
