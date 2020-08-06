@php
    @endphp
@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 角色列表')

@section('css')
    <style>
        .layui-table {
            table-layout: fixed;
        }

        .layui-table-overflow {
            overflow-x: auto;
        }
    </style>
@endsection

@section('content')

    @include('admin.common.crumb', ['title' => '角色列表'])

    <div class="layui-fluid">
        <div class="layui-row layui-col-space1">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <button class="layui-btn"
                                onclick="xadmin.open('添加角色','{{ split_url(route('admin.roles.create'))[1] }}', 700, 410)">
                            <i class="layui-icon layui-icon-add-circle"></i>添加
                        </button>
                    </div>
                    <div class="layui-card-body ">
                        <div class="layui-table-overflow">

                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th style="width: 100px">角色名</th>
                                    <th style="width: 135px">描述</th>
                                    <th style="width: 100px">标识</th>
                                    <th style="width: 120px">创建时间</th>
                                    <th style="width: 120px">修改时间</th>
                                    <th style="width: 60px">操作</th>
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
                    </div>

                    {{ $rolesData->links('admin.common.page', ['paginator' => $rolesData]) }}

                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-js')
    <script>

        function editRole(obj) {
            var obj = $(obj);
            xadmin.open('编辑', obj.data('url'), 700, 410);
        }

        function delRole(obj) {
            var obj = $(obj);
            layer.confirm('确认要删除这个角色吗？', function (i) {
                sendAjax({
                    'url': obj.data('url'),
                    'data': {_method: 'delete'},
                    'successCallBack': function (res) {
                        if (res.code === 2000000) {
                            layer.msg(res.message, {icon: 6, time: 1500}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg(res.message, {icon: 5, time: 1500});
                        }
                    },
                });
            });
        }

    </script>
@endsection
