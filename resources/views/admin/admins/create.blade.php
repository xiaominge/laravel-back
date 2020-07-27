@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 添加管理员')

@section('top-js')
    <script type="text/javascript" src="{{ asset('X-admin/js/xadmin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('X-admin/js/xm-select.js') }}"></script>
@endsection

@section('content')
    <style>
        .layui-form-item .layui-form-label {
            margin-top: -4px;
        }

        .layui-table tr td {
            padding-bottom: 20px;
        }

        body {
            background-color: #fff;
        }
    </style>
    <div class="layui-fluid" style="">
        <div class="layui-row">
            <form action="{{ route('admin.admins.store') }}" method="post" class="layui-form">
                @csrf
                <div class="layui-form-item ">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               lay-verify="name" lay-verType="tips" placeholder="请输入名称"
                               autocomplete="off"
                               class="layui-input {{ $errors->has('name') ? 'layui-form-danger' : '' }}">
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="password" class="layui-form-label">
                        <span class="x-red">*</span>密码
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="password" name="password" value="{{ old('password') }}"
                               lay-verify="required|pwd" lay-verType="tips" placeholder="请输入密码"
                               autocomplete="off"
                               class="layui-input {{ $errors->has('password') ? 'layui-form-danger' : '' }}">
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="email" class="layui-form-label">
                        <span class="x-red">*</span>邮箱
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="email" name="email" value="{{ old('email') }}"
                               lay-verify="required|email" lay-verType="tips"
                               autocomplete="off" placeholder="请输入邮箱"
                               class="layui-input {{ $errors->has('email') ? 'layui-form-danger' : '' }}">
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="role_id" class="layui-form-label">
                        <span class="x-red">*</span>角色
                    </label>
                    <div class="layui-input-inline" id="">
                        <div id="role_id" data-json="{{ $roles }}"></div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('bottom-js')
    <script>

        layui.use(['form', 'layer'], function () {
            $ = layui.jquery;
            var form = layui.form
                , layer = layui.layer;

            var roleJson = $('#role_id').data('json');
            var roleId = xmSelect.render({
                el: '#role_id',
                data: roleJson,
                layVerify: 'required',
                layVerType: 'tips',
                name: 'role_id'
            });

            form.verify({
                name: function (value) {
                    if (value.length < 1) {
                        return '用户名是必填项';
                    }
                    if (value.length < 2) {
                        return '用户名称至少得 2 个字符';
                    }
                    if (value.length > 20) {
                        return '用户名称最多 20 个字符';
                    }
                }
                , pwd: [/^[\S]{6,20}$/, "不包含空格的 6 到 12 位密码"]
            });

            // 监听提交
            form.on('submit(add)', function (formData) {
                // 发异步，把数据提交给 php
                $.ajax({
                    url: "{{ route('admin.admins.store') }}",
                    data: formData.field,
                    type: "post",
                    dataType: "json",
                    success: function (data) {
                        let successCallBack = function (index) {
                            var selectedRole = formData.field.role_id.split(',');
                            var firstRole = selectedRole[0];
                            layer.closeAll();
                            parent.layer.closeAll();
                            parent.element.tabChange("user-role", firstRole);
                        };
                        if (data.code == 2000000) {
                            layer.open({
                                title: '添加角色'
                                , content: data.message
                                , area: ['300px', '150px']
                                , btn: ['关闭']
                                , yes: successCallBack
                                , cancel: successCallBack
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

    </script>
@endsection
