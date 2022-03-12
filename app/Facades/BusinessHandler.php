<?php

namespace App\Facades;

use App\Constant\BusinessCode;
use  Illuminate\Support\Facades\Facade;

/**
 * @method static mixed succeed($data, $message, $statusCode, $businessCode, $header = []);
 * @method static mixed failed($message, $data, $statusCode, $businessCode, $header = []);
 * @method static mixed internalServerError($message = "", $data = [], $businessCode = BusinessCode::HTTP_INTERNAL_SERVER_ERROR, $headers = []);
 * @method static mixed notFound($message = "", $data = [], $businessCode = BusinessCode::HTTP_NOT_FOUND, $headers = []);
 */
class BusinessHandler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'businessHandler';
    }
}
