<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $fillable = [
        'role_name',
        'role_description',
        'is_active',
        'organization_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('permission_name', $permission)->exists();
    }

}
