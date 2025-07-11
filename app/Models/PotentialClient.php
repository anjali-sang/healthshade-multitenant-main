<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotentialClient extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'otp_expires_at',
        'otp_verified'
    ];
}
