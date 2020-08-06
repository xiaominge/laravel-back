@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 编辑权限')

@section('css')
    <style>
        body {
            background-color: #fff;
        }

        .layui-textarea {
            min-height: 50px;
        }
    </style>
@endsection

@section('content')
    @php
        @endphp
    <div class="layui-fluid" style="">
        <div class="layui-row">
            <form id="permission-edit-form" class="layui-form"
                  lay-filter="permission-edit-form"
                  action="{{ route('admin.permissions.update', $permission->id) }}"
                  method="post">
                @csrf
                @method('PUT')

                <div class="layui-form-item">
                    <label for="id" class="layui-form-label">
                        <span class="x-red">*</span>ID
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="id" name="id"
                               value="{{ old('id', $permission->id) }}"
                               lay-verType="tips" lay-verify="required"
                               autocomplete="off" disabled
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>权限名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name"
                               value="{{ old('name', $permission->name) }}"
                               lay-verType="tips" lay-verify="name"
                               placeholder="请输入权限名称" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="description" class="layui-form-label">
                        <span class="x-red">*</span>权限描述
                    </label>
                    <div class="layui-input-inline">
                        <textarea id="description" name="description"
                                  lay-verify="description" lay-verType="tips"
                                  placeholder="请输入权限描述"
                                  class="layui-textarea">{{ old('description', $permission->description) }}</textarea>
                    </div>
                </div>

                <div class="layui-form-item ">
                    <label for="pid" class="layui-form-label">
                        <span class="x-red">*</span>父级权限
                    </label>
                    <div class="layui-input-inline">
                        <select name="pid"
                                lay-filter="permission_select"
                                lay-verify="pid" lay-verType="tips"
                                lay-search>
                            <option value="">请选择父级权限</option>
                            <option value="0"
                            @if (old('pid', $permission->pid) == 0)  @endif>ROOT权限
                            </option>
                            @foreach($permissions as $p)
                                <option
                                    value="{{ $p->id }}"
                                @if ($p->id === old('pid', $permission->pid))  @endif
                                >{{ str_repeat("——", ($p->level)) . "" . $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="layui-form-item" id="icon">
                    <label for="icon" class="layui-form-label">
                        <span class="x-red">*</span>菜单图标
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="iconPicker" name="icon"
                               lay-verify="" lay-verType="tips"
                               lay-filter="iconPicker"
                               class="layui-input layui-disabled"
                               readonly>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="route" class="layui-form-label">
                        权限路由
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="permission-route"
                               name="route" value="{{ old('route', $permission->route) }}"
                               lay-verify="" lay-verType="tips"
                               placeholder="请输入权限路由" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item" id="">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="text" name="sort" value="{{ old('sort', $permission->sort) }}"
                               lay-verify="number" lay-verType="tips"
                               placeholder="" autocomplete="off"
                               class="layui-input">
                    </div>
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
        layui.use(['form', 'layer', 'iconPicker'], function () {
            var $ = layui.jquery;
            var iconPicker = layui.iconPicker;
            var form = layui.form, layer = layui.layer;
            form.render(null, 'permission-edit-form');

            iconPicker.render({
                elem: '#iconPicker',
                page: false,
                size: 'small',
                bodyHeight: '160px',
                bodyWidth: '206px',
                cellWidth: '36px',
            });

            /**
             * 选中图标 （常用于更新时默认选中图标）
             * @param filter lay-filter
             * @param iconName 图标名称，自动识别 fontClass/unicode
             */
            @if ($permission->icon)
            iconPicker.checkIcon('iconPicker', '{{ $permission->icon }}');
            @endif

            form.verify({
                name: function (value) {
                    if (value.length < 1) {
                        return '请输入权限名称';
                    }
                    if (value.length < 2) {
                        return '权限名称最小为 2 个字符';
                    }
                    if (value.length > 20) {
                        return '权限名称最大为 20 个字符';
                    }
                },
                description: function (value) {
                    if (value.length < 1) {
                        return '请输入权限描述';
                    }
                    if (value.length > 255) {
                        return '权限描述最多 255 个字符';
                    }
                },
                pid: function (value, item) {
                    if (value.length < 1) {
                        return '请选择父级权限';
                    }

                    if (value == $("#id").val()) {
                        return '父级权限不能选择本身';
                    }
                },
                route: function (value, item) {
                    if (value.length > 0) {
                        if (value.length < 2) {
                            return '路由最小为 2 个字符';
                        }
                        if (value.length > 200) {
                            return '路由最大为 200 个字符';
                        }
                    }
                },
                icon: function (value, item) {
                    if (value.length <= 0) {
                        return '请选择菜单图标';
                    }
                    if (value.length < 2) {
                        return '菜单图标最小为 2 个字符';
                    }
                    if (value.length > 45) {
                        return '菜单图标最大为 45 个字符';
                    }
                },
            });

            // 监听表单提交
            form.on('submit(edit)', function (data) {
                sendAjax({
                    'url': $('#permission-edit-form').attr('action'),
                    'data': data.field,
                });
                return false;
            });

            // 监听父级权限的 select 事件
            form.on('select(permission_select)', function (data) {
                console.log(data.value);
                if (data.value === '0') {
                    // 顶级权限 需要图标，移除图标输入框的隐藏类名
                    $('#icon').removeClass('layui-hide');
                    $('input[name="icon"]').attr('lay-verify', 'icon');
                    // 顶级权限 路由不必需
                    $('#permission-route').attr('lay-verify', 'route');
                    $('label[for="route"]').html('权限路由');
                } else {
                    // 不是顶级权限 不需要图标，隐藏图标输入框
                    $('#icon').addClass('layui-hide');
                    $('input[name="icon"]').attr('lay-verify', '');
                    // 不是顶级权限 需要路由
                    $('#permission-route').attr('lay-verify', 'required|route');
                    $('label[for="route"]').html('<span class="x-red">*</span>权限路由');
                }
                form.render(null, 'permission-edit-form');
                var o = $(".layui-form-select .layui-select-title input");
                var val = o.val().replace(/——/g, '');
                o.val(val);
            });

            $(function () {
                var permissionSelectVal = "{{ $permission->pid }}";
                $('select[name="pid"]')
                    .siblings("div.layui-form-select")
                    .find('dl dd[lay-value="' + permissionSelectVal + '"]')
                    .click();
            });
        });
    </script>
@endsection
