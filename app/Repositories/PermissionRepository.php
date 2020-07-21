<?php

namespace App\Repositories;

use App\Foundation\Repository\Repository;
use App\Models\Permission;

class PermissionRepository extends Repository
{

    public function model()
    {
        return Permission::class;
    }

    public function all()
    {
        return $this->m()->where('deleted_at', 0)->get();
    }

    public function allFormatPermissions()
    {
        $permissions = $this->all()->toArray();
        return $permissions ? $this->formatPermissions($permissions) : [];
    }

    /**
     * 格式化权限
     * 无限极分类
     *
     * @param     $permissions
     * @param int $pid
     * @param int $level
     *
     * @return array
     */
    private function formatPermissions($permissions, $pid = 0, $level = 0)
    {
        global $result;
        foreach ($permissions as $permission) {
            if ($permission['pid'] === $pid) {
                $result[] = array_merge($permission, ['level' => $level]);
                $this->formatPermissions($permissions, $permission['id'], $level + 1);
            }
        }

        return $result;
    }
}
