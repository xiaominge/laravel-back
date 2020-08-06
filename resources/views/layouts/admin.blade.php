<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        echo style('xadmin-font', 'layui', 'xadmin');
        echo script('layui');
        if (!isset($loadXadminJs) || $loadXadminJs == true) {
            echo script('xadmin');
        }
    @endphp
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .x-admin-sm .layui-form-item .layui-form-label, .x-admin-sm .layui-form-item .layui-form-mid {
            margin-top: -4px;
        }

        .x-admin-sm .layui-form-select dl {
            top: 32px;
        }

        .x-admin-sm .iconfont-icon {
            font-size: 14px;
        }

        .layui-btn .iconfont-icon {
            margin-right: 3px;
        }
    </style>
    @yield('css')
    @yield('top-js')
</head>
<body class="@yield('body-class', '')">

@section('content')
@show
<!-- 底部结束 -->

</body>
<script>
    layui.config({
        base: '{{ asset('js/layui') }}' + '/', // 自定义模块加载路径
    }).extend({
        xmSelect: 'xm-select/xm-select', // 多选下拉框
        iconPicker: 'iconPicker/iconPicker', // 图标选择工具
        iconExtend: 'icon-extend/icon-extend', // 图标扩展
        treeList: 'tree-list/tree-list', // 树形列表
    });

    layui.use('jquery', function () {
        var $ = layui.$;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
{!! script('own') !!}
@section('bottom-js')
@show
</html>
