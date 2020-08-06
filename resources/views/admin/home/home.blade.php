@extends('layouts.admin')
@section('title', config('app.name') . " - 管理平台")

@section('top-js')
    <script>
        // 是否开启刷新记忆tab功能
        var is_remember = false;
        // 解决登录状态失效，iframe 页面中登录的框架嵌套问题
        if (window != top) {
            top.location.href = window.location.href;
        }
    </script>
@endsection

@section('body-class', 'index')

@section('content')
    <!-- 顶部开始 -->
    @include('admin.home.top')
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
    <!-- 左侧菜单开始 -->
    @include('admin.home.left')
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content" style="margin-top: 2px">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
            <ul class="layui-tab-title" style="left: 14px;">
                <li class="home" style="padding-left: 13px;margin-top:-1px;">
                    <i class="layui-icon">&#xe68e;</i>我的桌面
                </li>
            </ul>
            <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                <dl>
                    <dd data-type="this">关闭当前</dd>
                    <dd data-type="other">关闭其它</dd>
                    <dd data-type="all">关闭全部</dd>
                </dl>
            </div>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe src='{{ split_url(route('admin.home.welcome'))[1] }}' frameborder="0" scrolling="yes"
                            class="x-iframe"></iframe>
                </div>
            </div>
            <div id="tab_show"></div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <style id="theme_style"></style>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
@endsection
@section('bottom-js')
    <script>
        layui.use('iconExtend', function (iconExtend) {
            iconExtend.loader('iconfont');
        });
    </script>
@endsection
