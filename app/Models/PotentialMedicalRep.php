<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotentialMedicalRep extends Model
{

    protected $guard = 'medical_reps';
    protected $fillable = [
        'email',
        'otp',
        'otp_expires_at',
        'otp_verified'
    ];
}
