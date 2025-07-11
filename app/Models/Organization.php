<?php

namespace App\Models;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use Billable;
    protected $fillable = [
        'id',
        'image',
        'name',
        'subscription_id',
        'email',
        'phone',
        'city',
        'state',
        'country',
        'pin',
        'address',
        'subscription_valid',
        'is_active',
        'is_deleted',
        'currency',
        'timezone',
        'date_format',
        'time_format',
        'theme',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'is_rep_org',
    ];

    // public static function fetchCode()
    // {
    //     $lastId = Organization::max('id') ?? 0;
    //     return $lastId + 1;
    // }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    public function locations()
    {
        return $this->hasMany(Location::class, 'org_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'organization_id');
    }

    public function productCategories()
{
    return $this->hasMany(ProductCategory::class);
}

}
