@php
    @endphp
@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 角色列表')

@section('top-js')
    <script type="text/javascript" src="{{ asset('X-admin/js/xadmin.js') }}"></script>
@endsection

@section('content')

    @include('admin.common.crumb', ['title' => '角色列表'])

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <button class="layui-btn"
                                onclick="xadmin.open('添加角色','{{ split_url(route('admin.roles.create'))[1] }}', 700, 470)">
                            <i class="layui-icon layui-icon-add-circle"></i>添加
                        </button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>角色名</th>
                                <th>描述</th>
                                <th>key</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th>删除时间</th>
                                <th>操作</th>
                            </thead>
                            <tbody>

                            @foreach($rolesData as $role)

                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>{{ $role->key }}</td>
                                    <td>{{ time_format($role->created_at) }}</td>
                                    <td>{{ time_format($role->updated_at) }}</td>
                                    <td style="color: #FF5722">{{ time_format($role->deleted_at) }}</td>
                                    <td class="td-manage">
                                        <a title="编辑"
                                           data-url="{{ split_url(route('admin.roles.edit', ['id' => $role->id]))[1] }}"
                                           onclick="editRole(this)"
                                           href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" data-url="{{ route('admin.roles.destroy', $role->id) }}"
                                           onclick="delRole(this)" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $rolesData->links('admin.common.page', ['paginator' => $rolesData]) }}

                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-js')
    <script>
        layui.use(['laydate', 'form'], function () {
            var laydate = layui.laydate;
            var form = layui.form;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });

        function editRole(obj) {
            var obj = $(obj);
            xadmin.open('编辑', obj.data('url'));
        }

        function delRole(obj) {
            var obj = $(obj);
            layer.confirm('确认要删除这个角色吗？', function (i) {
                // 发异步请求删除数据
                $.ajax({
                    url: obj.data('url'),
                    data: {_method: 'delete'},
                    type: "post",
                    dataType: "json",
                    success: function (res) {
                        if (res.code === 2000000) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000
                            }, function () {
                                location.reload();
                            });
                        } else {
                            layer.alert(res.message, {icon: 5}, function (index) {
                                layer.close(index);
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        let errorStr = "Error: " + jqXHR.responseJSON.message;
                        layer.alert(errorStr, {icon: 5}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            });
        }

    </script>
@endsection
