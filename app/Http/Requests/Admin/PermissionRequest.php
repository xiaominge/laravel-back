<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest as Base;

class PermissionRequest extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->routeName) {
            case 'admin.permissions.store':
            case 'admin.permissions.update':
                return [
                    'name' => 'required|min:2|max:45',
                    'description' => 'required|max:255',
                    'pid' => 'required|integer',
                    'icon' => 'nullable|min:2|max:45',
                    'route' => 'nullable|min:2|max:200',
                ];
                break;
        }
    }

    public function messages()
    {
        switch ($this->routeName) {
            case 'admin.permissions.store':
            case 'admin.permissions.update':
                return [
                    'name.required' => '请输入权限名称',
                    'name.min' => '权限名称最小为 2 个字符',
                    'name.max' => '权限名称最大为 20 个字符',
                    'description.required' => '请输入权限描述',
                    'description.max' => '权限描述最大为 255 个字符',
                    'pid.required' => '请选择父级权限',
                    'pid.integer' => '父级权限应该是数值',
                    'icon.min' => '菜单图标最短为 2 个字符',
                    'icon.max' => '菜单图标最长为 45 个字符',
                    'route.min' => '路由最短为 2 个字符',
                    'route.max' => '路由最长为 200 个字符',
                ];
                break;
        }
    }
}
