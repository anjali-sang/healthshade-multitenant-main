<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permissions::class, 'permission_id');
    }
}
