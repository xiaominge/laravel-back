@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 添加管理员')

@section('css')
    <style>
        body {
            background-color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="layui-fluid" style="">
        <div class="layui-row">
            <form id="admin-create-form" action="{{ route('admin.admins.store') }}" method="post"
                  class="layui-form">
                @csrf
                <div class="layui-form-item ">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               lay-verify="required|name" lay-verType="tips" placeholder="请输入名称"
                               autocomplete="off"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="password" class="layui-form-label">
                        <span class="x-red">*</span>密码
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="password" name="password"
                               value="{{ old('password') }}"
                               lay-verify="required|pwd" lay-verType="tips" placeholder="请输入密码"
                               autocomplete="off"
                               class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        6 到 12 个非空格字符
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
                               class="layui-input">
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
                        <button class="layui-btn" lay-submit="" lay-filter="add">保存</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('bottom-js')

    <script>

        layui.use(['form', 'layer', 'xmSelect'], function () {
            var $ = layui.$;
            var form = layui.form, layer = layui.layer;

            var roleJson = $('#role_id').data('json');
            var roleId = xmSelect.render({
                el: '#role_id',
                data: roleJson,
                size: 'mini',
                tips: '请选择角色',
                empty: '没有选项可供选择',
                searchTips: '搜索角色名称',
                layVerify: 'required',
                layVerType: 'tips',
                name: 'role_id',
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
                sendAjax({
                    'url': $('#admin-create-form').attr('action'),
                    'data': formData.field,
                    'closeLayerCallBack': function (index) {
                        layer.closeAll();
                        parent.layer.closeAll();
                        var firstRole = formData.field.role_id.split(',')[0];
                        parent.element.tabChange("user-role", firstRole);
                    },
                });
                return false;
            });
        });

    </script>
@endsection
