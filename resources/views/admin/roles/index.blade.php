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
                                onclick="xadmin.open('添加角色','{{ split_url(route('admin.roles.create'))[1] }}', 800, 600)">
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
                                    <td>{{ time_format($role->deleted_at) }}</td>
                                    <td class="td-manage">
                                        <a title="编辑"
                                           onclick="xadmin.open('编辑', '{{ split_url(route('admin.roles.edit', ['id' => $role->id]))[1] }}')"
                                           href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" onclick="member_del(this, '{{ $role->id }}')" href="javascript:;">
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

        /*用户-删除*/
        function member_del(obj, id) {
            layer.confirm('确认要删除吗？', function (index) {
                //发异步删除数据
                $(obj).parents("tr").remove();
                layer.msg('已删除!', {icon: 1, time: 1000});
            });
        }

    </script>
@endsection
