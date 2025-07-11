<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $fillable = [
        'permission_name',
        'permisson_description',
    ];
    // Permissions.php
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'roles_has_permission', 'permission_id', 'role_id');
    }

}