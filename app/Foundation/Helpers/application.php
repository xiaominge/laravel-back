<?php

use BeyondCode\DumpServer\Dumper;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use App\Repositories\RepositoryHandler;
use App\Services\ServiceHandle;
use App\Constant\DateFormat;
use App\Foundation\Util\Html;

if (!function_exists('repository')) {
    function repository()
    {
        return RepositoryHandler::getInstance();
    }
}

if (!function_exists('service')) {
    function service()
    {
        // dd(ServiceHandle::getInstance() === ServiceHandle::getInstance());
        return ServiceHandle::getInstance();
    }
}

if (!function_exists('business_handler')) {
    /**
     * @return App\Foundation\Response\BusinessHandler|mixed
     */
    function business_handler()
    {
        return app('businessHandler');
    }
}

if (!function_exists('user_business_handler')) {
    /**
     * @return App\Foundation\Response\UserBusinessHandler|mixed
     */
    function user_business_handler()
    {
        return app('userBusinessHandler');
    }
}

if (!function_exists('context')) {
    /**
     * Gets data that is saved only once in the life cycle
     *
     * @param null $key
     * @param null $default
     * @return \Illuminate\Foundation\Application|mixed
     */
    function context($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('context');
        }

        if (is_array($key)) {
            return app('context')->only($key);
        }

        $value = app('context')->$key;

        return is_null($value) ? value($default) : $value;
    }
}
if (!function_exists('auth_admin')) {
    /**
     * 判断后台用户是否登录
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function auth_admin()
    {
        return auth('admin')->user();
    }
}

if (!function_exists('auth_admin_id')) {
    /**
     * 判断后台用户是否登录
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function auth_admin_id()
    {
        return auth('admin')->user()->id;
    }
}

if (!function_exists('redis')) {
    /**
     * redis helpers
     *
     * @param string $connection
     * @return \Illuminate\Redis\Connections\Connection
     */
    function redis($connection = 'default')
    {
        return Redis::connection($connection);
    }
}

if (!function_exists('is_target_route')) {
    function is_target_route($targetRouteName)
    {
        return Route::currentRouteName() === $targetRouteName;
    }
}

if (!function_exists('d')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed $args
     *
     * @return void
     */
    function d(...$args)
    {
        foreach ($args as $x) {
            (new Dumper)->dump($x);
        }
    }
}

if (!function_exists('time_format')) {
    function time_format(int $time, string $format = DateFormat::FULL_FRIENDLY)
    {
        if (empty($time)) return '';
        return date($format, $time);
    }
}

function style()
{
    return Html::style(...func_get_args());
}

function script()
{
    return Html::script(...func_get_args());
}
