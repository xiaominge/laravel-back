<?php

namespace App\Services;

use App\Foundation\Service\Service;
use Illuminate\Support\Facades\Auth;

class PermissionService extends Service
{
    /**
     * 登录用户权限获取
     * @return mixed
     */
    public function getLoginAdminPermission()
    {
        $callback = function ($query) {
            $fields = [
                'permissions.id', 'permissions.name',
                'pid', 'icon', 'route', 'sort',
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
