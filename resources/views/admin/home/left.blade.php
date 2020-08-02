<div class="left-nav">
    <div id="side-nav" style="margin-top: -9px;">
        <ul id="nav">
            @foreach( $topMenus as $topMenu )
                <li>
                    <a href="javascript:;"
                       @if ($topMenu['route'])
                       lay-href="{{ split_url(route($topMenu['route']))[1] }}"
                        @endif
                    >
                        <i class="layui-icon {{ $topMenu['icon'] }}" lay-tips="{{ $topMenu['name'] }}"></i>
                        <cite>{{ $topMenu['name'] }}</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>

                    @if (isset($permissionsGroupByPid[$topMenu['id']]) && count($permissionsGroupByPid[$topMenu['id']]) > 0)
                        <ul class="sub-menu">
                            @foreach( $permissionsGroupByPid[$topMenu['id']] as $twoLevelMenu )
                                <li>
                                    <a onclick="xadmin.add_tab('{{ $twoLevelMenu['name'] }}', '{{ split_url(route($twoLevelMenu['route']))[1] }}')">
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>{{ $twoLevelMenu['name'] }}</cite>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
