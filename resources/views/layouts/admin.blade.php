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
    <link rel="stylesheet" href="{{ asset("X-admin/css/font.css") }}">
    <link rel="stylesheet" href="{{ asset("X-admin/css/xadmin.css") }}">
    <script src="{{ asset("X-admin/lib/layui/layui.js") }}" charset="utf-8"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
    @yield('top-js')
</head>
<body class="@yield('body-class', '')">

@section('content')
@show
<!-- 底部结束 -->

</body>
<script src="{{ asset('js/own.js') }}"></script>
<script>
    layui.use('jquery', function () {
        var $ = layui.$;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@section('bottom-js')
@show
</html>
