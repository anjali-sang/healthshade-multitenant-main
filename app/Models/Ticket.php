<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'image',
        'creator',
        'module',
        'description',
        'status',
        'priority',
        'closed_at',
        'organization_id',
        'images', 
        'note',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function creatorUser()
    {
        return $this->belongsTo(User::class, 'creator');
    }

}
