<div class="container">
    <div class="logo">
        <a href="{{ route('admin.home') }}">{{ config('app.name') . "管理平台" }}</a>
    </div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>

    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{ Auth::guard('admin')->user()->name }}</a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.open('修改密码', '{{ route('admin.home.password.change') }}', 400, 315)">修改密码</a>
                </dd>
                <dd>
                    <a style="color: #df5000" href="{{ route('admin.logout') }}">
                        <i class="iconfont-icon iconfont-icon-Logout"></i>退出
                    </a>
                </dd>
            </dl>
        </li>
    </ul>
</div>
