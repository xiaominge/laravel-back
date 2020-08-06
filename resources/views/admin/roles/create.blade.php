@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 添加角色')

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
            <form id="role-create-form" action="{{ route('admin.roles.store') }}" method="post" class="layui-form ">
                @csrf
                <div class="layui-form-item ">
                    <div class="layui-inline">
                        <label for="name" class="layui-form-label">
                            <span class="x-red">*</span>角色名称
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   lay-verType="tips" lay-verify="name" placeholder="请输入角色名称"
                                   autocomplete="off"
                                   class="layui-input">
                        </div>

                        <label for="key" class="layui-form-label">
                            <span class="x-red">*</span>角色标识
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" id="key" name="key" value="{{ old('key') }}"
                                   lay-verType="tips" lay-verify="key"
                                   autocomplete="off" placeholder="请输入角色标识"
                                   class="layui-input">
                        </div>
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="description" class="layui-form-label">
                        <span class="x-red">*</span>角色描述
                    </label>
                    <div class="layui-input-block">
                        <textarea lay-verType="tips" lay-verify="description" placeholder="请输入角色描述" id="description"
                                  name="description"
                                  class="layui-textarea">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="layui-form-item ">
                    <fieldset class="layui-elem-field layui-field-title" style="">
                        <legend>拥有权限</legend>
                    </fieldset>
                    <table class="layui-table">
                        <tbody class="">
                        @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <input lay-verType="tips" lay-verify="permissions" type="checkbox"
                                           name="permissions[]"
                                           lay-skin="primary"
                                           lay-filter="grandfather"
                                           value="{{ $permission->id }}"
                                           title="{{ $permission->name }}">
                                </td>
                                <td>
                                    @if(!empty($permission->children))
                                        <div class="">
                                            @foreach($permission->children as $twoLevel)
                                                <input lay-verType="tips" lay-verify="permissions" name="permissions[]"
                                                       lay-skin="primary"
                                                       type="checkbox"
                                                       title="{{ $twoLevel->name }}" value="{{ $twoLevel->id }}"
                                                       lay-filter="father">
                                                @if(!empty($twoLevel->children))
                                                    @foreach($twoLevel->children as $threeLevel)
                                                        <input lay-verType="tips" lay-verify="permissions"
                                                               name="permissions[]"
                                                               lay-skin="primary" type="checkbox"
                                                               title="{{ $threeLevel->name }}"
                                                               value="{{ $threeLevel->id }}">
                                                        @if($loop->last)
                                                            <hr/>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    <script type="text/javascript">
        layui.use(['form', 'layer'], function () {
            var $ = layui.jquery;
            var form = layui.form, layer = layui.layer;

            form.verify({
                name: function (value) {
                    if (value.length < 2) {
                        return '角色名称至少得 2 个字符';
                    }
                    if (value.length > 45) {
                        return '角色名称最多 45 个字符';
                    }
                }
                , key: function (value) {
                    if (value.length < 1) {
                        return '请输入角色标识';
                    }
                    if (value.length > 16) {
                        return '角色标识最多 16 个字符';
                    }
                }
                , description: function (value) {
                    if (value.length < 1) {
                        return '请输入角色描述';
                    }
                    if (value.length > 255) {
                        return '角色描述最多 255 个字符';
                    }
                }
                , permissions: function (value, item) {
                    var verifyElem = $('.layui-form').find("input[name='permissions[]']");
                    var isChecked = verifyElem.is(':checked');
                    if (!isChecked) {
                        return '请选择角色拥有的权限';
                    }
                }
            });

            // 监听提交
            form.on('submit(add)', function (data) {
                sendAjax({
                    'url': $('#role-create-form').attr('action'),
                    'data': data.field,
                });
                return false;
            });

            form.on('checkbox(grandfather)', function (data) {
                if (data.elem.checked) {
                    $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                    form.render();
                } else {
                    $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                    form.render();
                }
            });

            form.on('checkbox(father)', function (data) {
                if (data.elem.checked) {
                    $(data.elem).nextUntil("hr").prop("checked", true);
                    form.render();
                } else {
                    $(data.elem).nextUntil("hr").prop("checked", false);
                    form.render();
                }
            });
        });
    </script>
@endsection
