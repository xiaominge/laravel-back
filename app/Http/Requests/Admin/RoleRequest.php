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
            case 'admin.roles.update':
                return [
                    'name' => 'required|min:2|max:45',
                    'description' => 'max:255',
                    'permissions' => 'required',
                ];
                break;
        }
    }
}
