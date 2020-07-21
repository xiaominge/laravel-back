<?php

namespace App\Models;

use App\Foundation\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'route',
        'icon',
        'pid'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id')
            ->where('role_permission.deleted_at', 0)
            ->where('roles.deleted_at', 0);
    }

}
