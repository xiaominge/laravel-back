@extends('layouts.admin')
@section('title', config('app.name') . " - 管理平台 - 欢迎页面")

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span
                                class="x-red">{{ Auth::guard('admin')->user()->name }}</span> ！
                            当前时间：{{ date("Y-m-d H:i:s") }}
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">数据统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>管理员</h3>
                                    <p>
                                        <cite>{{ $statistics['admin'] }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>角色数</h3>
                                    <p>
                                        <cite>{{ $statistics['role'] }}</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">系统信息</div>
                    <div class="layui-card-body ">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <th>服务器地址</th>
                                <td>{{ $server['SERVER_NAME'] }}</td>
                            </tr>
                            <tr>
                                <th>操作系统</th>
                                <td>{{ php_uname() }}</td>
                            </tr>
                            <tr>
                                <th>运行环境</th>
                                <td>{{ $server['SERVER_SOFTWARE'] }}</td>
                            </tr>
                            <tr>
                                <th>PHP版本</th>
                                <td>{{ phpversion() }}</td>
                            </tr>
                            <tr>
                                <th>Laravel</th>
                                <td>{{ app()::VERSION }}</td>
                            </tr>
                            <tr>
                                <th>上传附件限制</th>
                                <td>{{ ini_get('upload_max_filesize') }}</td>
                            </tr>
                            <tr>
                                <th>执行时间限制</th>
                                <td>{{ ini_get('max_execution_time') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <style id="welcome_style"></style>
        </div>
    </div>
@endsection
@section('bottom-js')
@endsection
