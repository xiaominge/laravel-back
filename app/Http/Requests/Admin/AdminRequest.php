<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest as Base;

class AdminRequest extends Base
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->routeName) {
            case 'admin.admins.store':
                return [
                    'name' => 'required|min:2|max:20',
                    'email' => 'required|email',
                    'role_id' => 'required',
                    'password' => 'required|min:6|max:20'
                ];
                break;
            case 'admin.admins.update':
                return [
                    'name' => 'required|min:2|max:20',
                    'role_id' => 'required',
                ];
                break;
            case 'user.admins.password':
                return [
                    'old_password' => 'required',
                    'password' => 'required|min:6|max:20'
                ];
                break;
        }
    }

    public function messages()
    {
        switch ($this->routeName) {
            case 'admin.admins.store':
                return [
                    'name.required' => '用户名是必填项',
                    'name.min' => '用户名称至少得 2 个字符',
                    'name.max' => '用户名称最多 20 个字符',
                    'email.required' => '邮箱是必填项',
                    'email.email' => '邮箱格式不正确',
                    'role_id.required' => '用户角色是必填项',
                    'password.required' => '密码是必填项',
                    'password.min' => '密码最少 6 个字符',
                    'password.max' => '密码最多 20 个字符',
                ];
                break;
            case 'admin.admins.update':
                return [
                    'name.required' => '用户名是必填项',
                    'name.min' => '用户名称至少得 2 个字符',
                    'name.max' => '用户名称最多 20 个字符',
                    'role_id.required' => '用户角色是必填项',
                ];
                break;
            case 'admin.admins.password':
                return [
                    'old_password.required' => '旧密码是必填项',
                    'password.required' => '新密码是必填项',
                    'password.min' => '新密码最少 6 个字符',
                    'password.max' => '新密码最多 20 个字符',
                ];
                break;
        }
    }
}
