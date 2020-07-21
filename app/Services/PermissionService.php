<?php

namespace App\Services;

use App\Foundation\Service\Service;
use Illuminate\Support\Facades\Auth;

class PermissionService extends Service
{
    public function getLoginAdminPermission()
    {
        $callback = function ($query) {
            $fields = [
                'permissions.id', 'permissions.name',
                'pid', 'icon', 'route',
            ];
            $query->select($fields);
        };
        $permissions = Auth::guard('admin')->user()
            ->roles()->with(['permissions' => $callback])
            ->get()->pluck('permissions')
            ->collapse()->unique('id');

        return $permissions;
    }
}
