@extends('layouts.admin')
@section('title', config('app.name') . " - 管理平台 - 修改密码")

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form id="password-change-form" class="layui-form" lay-filter="create-form"
                              method="POST" action="{{ route('admin.home.password.do-change') }}"
                        >
                            {{ method_field('PUT') }}
                            @csrf

                            <div class="layui-form-item">
                                <label class="layui-form-label">旧密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="old_password"
                                           lay-verify="required|pwd" lay-verType="tips"
                                           placeholder="请输入旧密码"
                                           autocomplete="off"
                                           value="{{ old('old_password') }}"
                                           class="layui-input {{ $errors->has('old_password') ? 'layui-form-danger' : '' }}">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password"
                                           lay-verify="required|pwd" lay-verType="tips"
                                           placeholder="请输入新密码"
                                           autocomplete="off"
                                           value="{{ old('password') }}"
                                           class="layui-input {{ $errors->has('password') ? 'layui-form-danger' : '' }}">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">确认密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="confirm_password"
                                           lay-verify="required|confirmPwd" lay-verType="tips"
                                           placeholder="请重复输入新密码"
                                           autocomplete="off"
                                           value="{{ old('confirm_password') }}"
                                           class="layui-input {{ $errors->has('confirm_password') ? 'layui-form-danger' : '' }}">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit="" lay-filter="submit-filter">
                                        立即提交
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom-js')
    <script>
        layui.use(['layer', 'form'], function () {
            var $ = layui.$,
                layer = layui.layer,
                form = layui.form;

            form.render(null, 'create-form');
            form.verify({
                pwd: function (value) {
                    if (!/^[\S]{6,20}$/.test(value)) {
                        return "不包含空格的 6 到 12 位密码";
                    }
                },
                confirmPwd: function (value) {
                    if (!/^[\S]{6,20}$/.test(value)) {
                        return "不包含空格的 6 到 12 位密码";
                    }
                    if (value != $("input[type='password']").eq(1).val()) {
                        return "两次输入的密码不一致";
                    }
                },
            });

            form.on('submit(submit-filter)', function (data) {
                sendAjax({
                    'url': $("#password-change-form").action,
                    'data': data.field,
                    'successCallBack': function (res) {
                        if (res.code === 2000000) {
                            layer.msg(res.message, {icon: 6, time: 1500}, function () {
                                layer.closeAll();
                                parent.layer.closeAll();
                                top.location.reload();
                            });
                        } else {
                            layer.msg(res.message, {icon: 5, time: 2500});
                        }
                    },
                });
                return false;
            });
        });
    </script>
@endsection
