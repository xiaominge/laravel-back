<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest as BaseRequest;

class RoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->routeName) {
            case 'admin.roles.store':
                return [
                    'name' => 'required|min:2|max:45',
                    'key' => 'required|max:16',
                    'description' => 'required|max:255',
                    'permissions' => 'required',
                ];
                break;
            case 'admin.roles.update':
                return [
                    'name' => 'required|min:2|max:45',
                    'description' => 'required|max:255',
                    'permissions' => 'required',
                ];
                break;
        }
    }

    public function messages()
    {
        switch ($this->routeName) {
            case 'admin.roles.store':
                return [
                    'name.required' => '请输入角色名称',
                    'name.min' => '角色名称最小 2 个字符',
                    'name.max' => '角色名称最大 45 个字符',
                    'key.required' => '请输入角色标识',
                    'key.max' => '角色标识最大 16 个字符',
                    'description.required' => '请输入角色描述',
                    'description.max' => '角色描述最大 255 个字符',
                    'permissions.required' => '请选择角色拥有的权限',
                ];
                break;

            case 'admin.roles.update':
                return [
                    'name.required' => '请输入角色名称',
                    'name.min' => '角色名称最小 2 个字符',
                    'name.max' => '角色名称最大 45 个字符',
                    'key.required' => '请输入角色标识',
                    'key.max' => '角色标识最大 16 个字符',
                    'description.required' => '请输入角色描述',
                    'description.max' => '角色描述最大 255 个字符',
                    'permissions.required' => '请选择角色拥有的权限',
                ];
                break;
        }
    }

}
