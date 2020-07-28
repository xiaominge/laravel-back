@php
    @endphp
@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 管理员列表')

@section('top-js')
    <script type="text/javascript" src="{{ asset('X-admin/js/xadmin.js') }}"></script>
@endsection

@section('content')

    @include('admin.common.crumb', ['title' => '管理员列表'])

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <button class="layui-btn"
                                onclick="xadmin.open('添加管理员','{{ split_url(route('admin.admins.create'))[1] }}', 500, 400)">
                            <i class="layui-icon layui-icon-add-circle"></i>添加
                        </button>
                    </div>
                    <div class="layui-card-body ">
                        <div class="layui-tab layui-tab-brief" lay-filter="user-role">
                            <ul class="layui-tab-title" id="user-role-ul" data-current-role="{{ $roleId }}">
                                @foreach($roles as $index => $role)
                                    <li class="@if($roleId == $role->id) layui-this @endif"
                                        lay-id="{{ $role->id }}">
                                        {{ $role->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>邮箱</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th>删除时间</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ time_format($admin->created_at) }}</td>
                                    <td>{{ time_format($admin->updated_at) }}</td>
                                    <td style="color: #FF5722">{{ time_format($admin->deleted_at) }}</td>
                                    <td class="td-manage">
                                        <a title="编辑"
                                           data-url="{{ split_url(route('admin.admins.edit', ['id' => $admin->id]))[1] }}"
                                           onclick="editAdmin(this)"
                                           href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" data-url="{{ route('admin.admins.destroy', $admin->id) }}"
                                           onclick="delAdmin(this)" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $admins->links('admin.common.page', ['paginator' => $admins]) }}

                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-js')
    <script>
        layui.use(['laydate', 'form', 'element'], function () {
            var laydate = layui.laydate;
            var form = layui.form;
            var element = layui.element;
            // 监听Tab切换
            element.on('tab(user-role)', function (data) {
                var layId = this.getAttribute('lay-id');
                location.href = '{{ route("admin.admins.index") }}' + '?id=' + layId;
            });
        });

        function editAdmin(obj) {
            var obj = $(obj);
            xadmin.open('编辑', obj.data('url'), 500, 400);
        }

        function delAdmin(obj) {
            var obj = $(obj);
            layer.confirm('确认要删除这个管理员吗？', function (i) {
                sendAjax({
                    'url': obj.data('url'),
                    'data': {_method: 'delete'},
                    'successCallBack': function (res) {
                        if (res.code === 2000000) {
                            layer.msg(res.message, {icon: 6, time: 1500}, function () {
                                element.tabChange("user-role", $("#user-role-ul").data('current-role'));
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
@section('bottom-js')
@endsection
