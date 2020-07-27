@extends('layouts.admin')
@section('title', config('app.name') . " - 管理平台登录")
@section('css')
    <link rel="stylesheet" href="{{ asset("X-admin/css/login.css") }}">
@endsection

@section('top-js')
    <script>
        // 解决登录状态失效，iframe 页面中登录的框架嵌套问题
        if (window != top) {
            top.location.href = window.location.href;
        }
    </script>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
@endsection

@section('content')
    <div class="login layui-anim layui-anim-up">
        <div class="message">{{ config('app.name') . " - 管理平台登录" }}</div>
        <div id="darkbannerwrap"></div>

        <form method="post" action="{{ route('admin.login') }}" class="layui-form">
            @csrf
            <input name="name" value="{{ old('name') }}" placeholder="用户名" type="text" lay-verType="tips"
                   lay-verify="required|name"
                   class="layui-input">
            <hr class="hr15">
            <input name="password" lay-verType="tips" lay-verify="required|pwd" placeholder="密码" type="password"
                   class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20">
        </form>
    </div>
@endsection

@section('bottom-js')
    <script>
        $(function () {
            layui.use('form', function () {
                var form = layui.form;

                form.verify({
                    name: function (value) {
                        if (value.length < 2) {
                            return '用户名至少要 2 个字符';
                        }
                    }
                    , pwd: [/^[\S]{6,12}$/, '密码必须6到20位']
                });

                form.on('submit(login)', function (formData) {
                    $.ajax({
                        url: "{{ route('admin.login') }}",
                        data: formData.field,
                        type: "post",
                        dataType: "json",
                        success: function (data) {
                            if (data.code == 2000000) {
                                layer.msg(data.message, {
                                    icon: 6,
                                    time: 1000
                                }, function () {
                                    location.href = "{{ route('admin.home') }}";
                                });
                            } else {
                                layer.alert("" + data.message, {icon: 5}, function (index) {
                                    layer.close(index);
                                });
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            let error = "Error: " + jqXHR.responseJSON.message;
                            layer.alert(error, {icon: 5}, function (index) {
                                layer.close(index);
                            });
                        }
                    });

                    return false;
                });

            });
        })
    </script>
@endsection
