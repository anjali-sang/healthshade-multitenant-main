<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'chartnumber',
        'address',
        'city',
        'state',
        'country',
        'pin_code',
        'is_active',
        'organization_id',
        'ins_type',
        'provider',
        'icd',
        'account_number',
        'drug',
        'dose',
        'frequency',
        'location',
        'pa_expires',
        'date_given',
        'paid',
        'our_cost',
        'pt_copay',
        'profit',
    ];
}
