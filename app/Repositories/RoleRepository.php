<?php

namespace App\Repositories;

use App\Foundation\Repository\Repository;
use App\Exceptions\BusinessException;
use App\Models\Role;

class RoleRepository extends Repository
{

    public function model()
    {
        return Role::class;
    }

    /**
     * 获取所有后台角色
     *
     * @return mixed
     */
    public function getAdminRoles()
    {
        return $this->model->where('deleted_at', 0)->get();
    }

    /**
     * 根据角色ID 获得角色对象
     *
     * @param $id
     *
     * @return mixed
     * @throws BusinessException
     */
    public function findById($id)
    {
        $model = $this->model->where('deleted_at', 0)->find($id);
        if (!$model) {
            throw new BusinessException('角色不存在');
        }

        return $model;
    }

    public function paginateGetAllRoles($num)
    {
        return $this->m()->paginate($num);
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
