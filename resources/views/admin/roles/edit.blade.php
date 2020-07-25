@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 角色编辑')

@section('top-js')
    <script type="text/javascript" src="{{ asset('X-admin/js/xadmin.js') }}"></script>
@endsection

@section('content')
    <style>
        .layui-form-item .layui-form-label {
            margin-top: -4px;
        }

        .layui-table tr td {
            padding-bottom: 20px;
        }
    </style>
    <div class="layui-fluid" style="background-color: #fff;">
        <div class="layui-row">
            <form id="roles-edit-form" action="{{ route('admin.roles.update', $role->id) }}" method="post"
                  class="layui-form ">
                @csrf
                @method('PUT')
                <div class="layui-form-item ">
                    <div class="layui-inline">
                        <label for="name" class="layui-form-label">
                            <span class="x-red">*</span>角色名称
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                                   required="" lay-verify="name" placeholder="请输入角色名称"
                                   autocomplete="off"
                                   class="layui-input {{ $errors->has('name') ? 'layui-form-danger' : '' }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="description" class="layui-form-label">
                        <span class="x-red">*</span>角色描述
                    </label>
                    <div class="layui-input-block">
                        <textarea lay-verify="description" placeholder="请输入角色描述" id="description" name="description"
                                  class="layui-textarea {{ $errors->has('description') ? 'layui-form-danger' : '' }}">{{ old('description', $role->description) }}</textarea>
                        @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="layui-form-item ">
                    <fieldset class="layui-elem-field layui-field-title" style="">
                        <legend>拥有权限</legend>
                    </fieldset>
                    <table class="layui-table">
                        <tbody class="">
                        @php
                            $currentChecked = explode(',', old('permissions', $currentPermissions));
                        @endphp
                        @foreach($permissions as $permission)
                            @php
                                @endphp
                            <tr>
                                <td>
                                    <input lay-verify="permissions" type="checkbox" name="permissions[]"
                                           lay-skin="primary"
                                           lay-filter="grandfather"
                                           @if(in_array($permission->id, $currentChecked))
                                           checked
                                           @endif
                                           value="{{ $permission->id }}"
                                           title="{{ $permission->name }}">
                                </td>
                                <td>
                                    @if(!empty($permission->children))
                                        <div class="">
                                            @foreach($permission->children as $twoLevel)
                                                <input lay-verify="permissions" name="permissions[]" lay-skin="primary"
                                                       type="checkbox"
                                                       @if(in_array($twoLevel->id, $currentChecked))
                                                       checked
                                                       @endif
                                                       title="{{ $twoLevel->name }}" value="{{ $twoLevel->id }}"
                                                       lay-filter="father">
                                                @if(!empty($twoLevel->children))
                                                    @foreach($twoLevel->children as $threeLevel)
                                                        <input lay-verify="permissions" name="permissions[]"
                                                               lay-skin="primary" type="checkbox"
                                                               @if(in_array($threeLevel->id, $currentChecked))
                                                               checked
                                                               @endif
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
                        <button class="layui-btn" lay-submit="" lay-filter="edit">保存</button>
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

            form.verify({
                name: function (value) {
                    if (value.length < 2) {
                        return '角色名称至少得 2 个字符';
                    }
                    if (value.length > 45) {
                        return '角色名称最多 45 个字符';
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
                , permissions: function (value) {
                    if (value.length < 1) {
                        return '请选择角色拥有的权限';
                    }
                }
            });

            // 监听提交
            form.on('submit(edit)', function (data) {
                // 发异步，把数据提交给 php
                $.ajax({
                    url: $("#roles-edit-form").attr('action'),
                    data: data.field,
                    type: "post",
                    dataType: "json",
                    success: function (data) {
                        let successCallBack = function (index) {
                            // 关闭当前弹窗
                            layer.close(index);
                            var parentIndex = parent.layer.getFrameIndex(window.name);
                            // 关闭父级弹窗
                            parent.layer.close(parentIndex);
                            parent.location.reload();
                        };
                        if (data.code == 2000000) {
                            layer.open({
                                title: '编辑角色'
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
