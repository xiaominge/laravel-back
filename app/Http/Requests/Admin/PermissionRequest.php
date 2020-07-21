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
                    'icon' => 'nullable',
                    'route' => 'nullable|min:2|max:255'
                ];
                break;
        }
    }
}
