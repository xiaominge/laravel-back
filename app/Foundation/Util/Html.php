<?php

namespace App\Foundation\Util;

use App\Foundation\HtmlBuilder;

class Html
{
    /**
     * 静态文件别名加载
     * @return string
     */
    protected static function staticFile()
    {
        $args = func_get_args();
        $type = debug_backtrace()[1]['function'];
        $aliasKey = $type . 'Alias';
        $aliasConfig = \Config::get('extend.' . $aliasKey);
        $version = \Config::get('extend.version');
        $array = array_map(function ($alias) use ($type, $aliasConfig, $version) {
            if (isset($aliasConfig[$alias])) {
                return app(HtmlBuilder::class)->$type($aliasConfig[$alias] . "?v=" . $version);
            }
        }, $args);
        return implode('', array_filter($array));
    }

    /**
     * JS 脚本别名加载
     * @return string
     */
    public static function script()
    {
        return self::staticFile(...func_get_args());
    }

    /**
     * css 文件别名加载
     * @return string
     */
    public static function style()
    {
        return self::staticFile(...func_get_args());
    }
}
