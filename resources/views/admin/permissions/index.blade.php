@php
    //dd($permissions);
@endphp
@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 权限列表')

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

    @include('admin.common.crumb', ['title' => '权限列表'])

    <div class="layui-fluid">
        <div class="layui-row layui-col-space1">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <button class="layui-btn"
                                onclick="xadmin.open('添加权限','{{ split_url(route('admin.permissions.create'))[1] }}', 530, 410)">
                            <i class="layui-icon layui-icon-add-circle"></i>添加
                        </button>
                        <button class="layui-btn" data-action="expand">
                            <i class="iconfont-icon iconfont-icon-nodeexpand"></i>展开
                        </button>
                        <button class="layui-btn" data-action="collapse">
                            <i class="iconfont-icon iconfont-icon-nodecollapse"></i>收起
                        </button>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-table-overflow">
                            <table class="layui-table">
                                <thead class="">
                                <tr>
                                    <th style="width:50px">ID</th> <!-- width:50px -->
                                    <th style="width:50px">排序</th> <!-- width:50px -->
                                    <th style="width:328px">名称 / 路由</th> <!-- width:270px -->
                                    <th style="width:100px">父级权限</th> <!-- width:65px -->
                                    <th style="width:50px;text-align: center">菜单图标</th> <!-- width:65px -->
                                    <th style="width:250px">创建时间 / 修改时间</th> <!-- width:250px -->
                                    <th style="width:60px">操作</th>
                                </thead>
                                <tbody class="tree-list">

                                @foreach($permissions as $permission)
                                    <tr item-id='{{ $permission->id }}' pid='{{ $permission->pid ?: 0 }}'>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->sort }}</td>
                                        <td>
                                            {!! str_repeat('&nbsp;', ($permission->level -1 ) * 8) !!}
                                            <i data-action></i>
                                            {{ $permission->name }} / {{ $permission->route ?: '( no route )' }}
                                        </td>
                                        <td>{{ $permission->parentName }} ( {{ $permission->pid }} )</td>
                                        <td style="text-align: center">
                                            <i class="layui-icon {{ $permission->icon }}"></i>
                                        </td>
                                        <td>{{ time_format($permission->created_at) }}
                                            / {{ time_format($permission->updated_at) }}</td>
                                        <td class="td-manage">
                                            <a title="添加"
                                               data-url="{{ split_url(route('admin.permissions.create', ['pid' => $permission->id]))[1] }}"
                                               onclick="addPermission(this)"
                                               href="javascript:;">
                                                <i class="layui-icon layui-icon-add-circle"></i>
                                            </a>
                                            <a title="编辑"
                                               data-url="{{ split_url(route('admin.permissions.edit', ['id' => $permission->id]))[1] }}"
                                               onclick="editPermission(this)"
                                               href="javascript:;">
                                                <i class="layui-icon">&#xe642;</i>
                                            </a>
                                            <a title="删除"
                                               data-url="{{ route('admin.permissions.destroy', $permission->id) }}"
                                               onclick="delPermission(this)" href="javascript:;">
                                                <i class="layui-icon">&#xe640;</i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-js')
    <script>

        function addPermission(obj) {
            var obj = $(obj);
            xadmin.open('添加', obj.data('url'), 530, 410);
        }

        function editPermission(obj) {
            var obj = $(obj);
            xadmin.open('编辑', obj.data('url'), 530, 410);
        }

        function delPermission(obj) {
            var obj = $(obj);
            layer.confirm('确认要删除这个权限吗？', function (i) {
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

        layui.use(['jquery', 'iconExtend', 'treeList'], function () {
            var $ = layui.jquery;
            var iconExtend = layui.iconExtend;
            var treeList = layui.treeList;

            iconExtend.loader('iconfont');
            treeList.render({
                parentId: 'pid',
            });
        });
    </script>
@endsection
