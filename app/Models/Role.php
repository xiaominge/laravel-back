<?php

namespace App\Models;

use App\Foundation\Model;

class Role extends Model
{
    protected $table = 'roles';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'key',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id')
            ->where('role_permission.deleted_at', 0)
            ->where('permissions.deleted_at', 0);
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_role', 'role_id', 'admin_id')
            ->where('admin_role.deleted_at', 0)
            ->where('admins.deleted_at', 0);
    }

}
