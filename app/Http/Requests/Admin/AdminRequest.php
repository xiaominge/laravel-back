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
                    'role_id' => 'required'
                ];
                break;
        }
    }
}
