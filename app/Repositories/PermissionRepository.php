<?php

namespace App\Repositories;

use App\Exceptions\BusinessException;
use App\Foundation\Repository\Repository;
use App\Models\Permission;

class PermissionRepository extends Repository
{

    public function model()
    {
        return Permission::class;
    }

    public function findById($id)
    {
        $model = $this->m()->where('deleted_at', 0)->find($id);
        if (!$model) {
            throw new BusinessException('权限不存在或已被删除');
        }
        return $model;
    }

    public function all()
    {
        return $this->m()->where('deleted_at', 0)->get();
    }

    /**
     * 获取根据 pid 生成的权限数状列表
     * @return array
     */
    public function allNestPermissions()
    {
        $permissions = $this->all();
        return $permissions ? $this->formatNestPermissions($permissions) : [];
    }

    public function allFormatPermissions()
    {
        $permissions = $this->all()->keyBy('id')->toArray();
        return $permissions ? $this->formatPermissions($permissions) : [];
    }

    /**
     * 格式化权限 增加 level 字段
     *
     * @param     $permissions
     * @param int $pid
     * @param int $level
     *
     * @return array
     */
    private function formatPermissions($permissions, $pid = 0, $level = 1)
    {
        static $result;
        foreach ($permissions as $permission) {
            if ($permission['pid'] === $pid) {
                $result[] = array_merge($permission, [
                    'level' => $level,
                    'parentName' => $permissions[$pid]['name'] ?? 'ROOT权限',
                ]);
                $this->formatPermissions($permissions, $permission['id'], $level + 1);
            }
        }

        return $result;
    }

    /**
     * 格式化权限列表为树状结构
     * @param $permissions
     * @param int $pid
     * @return array
     */
    private function formatNestPermissions($permissions, $pid = 0)
    {
        // 不能用 static
        $result = array();
        foreach ($permissions as $v) {
            if ($v['pid'] == $pid) {
                foreach ($permissions as $subVal) {
                    if ($subVal['pid'] == $v['id']) {
                        $v['children'] = $this->formatNestPermissions($permissions, $v['id']);
                        break;
                    }
                }
                $result[] = $v;
            }
        }
        return $result;
    }

    /**
     * 根据现有的权限获得所有下级权限
     *
     * @param $needs
     * @param $permissions
     *
     * @return array
     */
    public function getPermissionsList($needs, $permissions)
    {
        global $list;
        foreach ($needs as $value) {
            $list[] = (int)$value;
            $_temp = $permissions->where('pid', $value)->pluck('id')->toArray();
            if ($_temp) {
                $this->getPermissionsList($_temp, $permissions);
            }
        }

        return $list;
    }

    /**
     * 根据现有的权限id获取上级权限
     *
     * @param $needs
     * @param $permissions
     *
     * @return array
     */
    public function getSuperiorPermissions($needs, $permissions)
    {
//        dd($needs, $permissions);
        global $list;
        foreach ($needs as $value) {
            $list[] = (int)$value;
            $_temp = $permissions->where('id', $value)->pluck('pid')->toArray();
            if (array_first($_temp) != 0) {
                $this->getSuperiorPermissions($_temp, $permissions);
            }
        }

        return $list;
    }
}
