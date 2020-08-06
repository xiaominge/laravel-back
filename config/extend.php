<?php

/*
|--------------------------------------------------------------------------
| 拓展配置文件
|--------------------------------------------------------------------------
| 配置文件规范， => 符号前后各一个空格
|--------------------------------------------------------------------------
*/

return [
    // 静态文件更新缓存用
    'version' => '1.1',

    // 样式文件别名配置
    'styleAlias' => [
        'xadmin-font' => 'X-admin/css/font.css',
        'layui' => 'X-admin/lib/layui/css/layui.css',
        'xadmin' => 'X-admin/css/xadmin.css',
        'login' => 'X-admin/css/login.css',
    ],

    // JS 文件别名配置
    'scriptAlias' => [
        'own' => 'js/own.js',
        'layui' => 'X-admin/lib/layui/layui.js',
        'xadmin' => 'X-admin/js/xadmin.js',
    ],
];
