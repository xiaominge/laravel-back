<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class BaseRequest extends FormRequest
{
    protected $routeName;

    public function __construct()
    {
        parent::__construct();
        $this->routeName = Route::currentRouteName();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
