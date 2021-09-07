<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@isset($breadcrumb){{ last($breadcrumb)['title'] }}@endisset - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="/public/vendor/layui-v2.4.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/public/admin/css/lightCMSAdmin.css" media="all">
    @yield('css')
    @yield('head-js')
</head>
<body class="layui-layout-body">
@php
    $user = \Auth::guard('admin')->user();
    $isSuperAdmin = in_array($user->id, config('light.superAdmin'));
@endphp
<div id="LAY_app">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left nav-menu">
            <li class="layui-nav-item layadmin-flexible" lay-unselect>
                <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                  <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                </a>
            </li>
            @foreach(App\Repository\Admin\MenuRepository::allRoot() as $v)
                @if($isSuperAdmin || $user->can($v->name))
                    <li class="layui-nav-item @if(!empty($light_menu) && $v->id == $light_menu['id']) layui-this @endif"><a href="{{ $v->url }}">{{ $v->name }}</a></li>
                @endif
            @endforeach
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <i class="layui-icon layui-icon-username" style="font-size: 20px; color: #009688;"></i>
                    {{ \Auth::guard('admin')->user()->name }}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ route('admin::adminUser.edit', ['id' => \Auth::guard('admin')->user()->id]) }}">编辑用户</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="{{ route('admin::logout') }}">退了</a></li>
        </ul>
        <ul class="layui-nav hamburger">
            <li class="layui-nav-item">
            <a href="javascript:;" layadmin-event="horizontal-flexible" title="顶部导航">
                    <i class="layui-icon layui-icon-shrink-right" id="LAY_app_horizontal_flexible"></i>
            </a>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-side-menu layui-bg-black">
        <div class="layui-side-scroll">
            <div class="layui-logo">{{ config('app.name') }} 管理后台</div>
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                @isset($light_menu['children'])
                @foreach($light_menu['children']->groupBy('group') as $k => $menu)
                    @if($k != '' && collect($menu)->where('status', App\Model\Admin\Menu::STATUS_ENABLE)->count() > 0)
                <li class="layui-nav-item layui-nav-itemed">
                    @foreach($menu as $sub)
                        @if(intval($sub['status']) === App\Model\Admin\Menu::STATUS_ENABLE && ($isSuperAdmin || $user->can($sub['name'])))
                            <a class="" href="javascript:;">{{ $k }}</a>
                            @break
                        @endif
                    @endforeach

                    <dl class="layui-nav-child">
                        @foreach($menu as $sub)
                            @if(intval($sub['status']) === App\Model\Admin\Menu::STATUS_ENABLE && ($isSuperAdmin || $user->can($sub['name'])))
                                <dd @if($sub['route'] == $light_cur_route) class="layui-this" @endif><a href="{{ $sub['url'] }}">{{ $sub['name'] }}</a></dd>
                            @endif
                        @endforeach
                    </dl>
                </li>
                    @endif
                @endforeach
                @endisset
                    @isset($autoMenu)
                        <li class="layui-nav-item layui-nav-itemed">
                            <a class="" href="javascript:;">系统菜单</a>
                            <dl class="layui-nav-child">
                                @foreach($autoMenu as $v)
                                    <dd @if(isset($entity) && $v['id'] == intval($entity)) class="layui-this" @endif><a href="{{ $v['url'] }}">{{ $v['name'] }}</a></dd>
                                @endforeach
                            </dl>
                        </li>
                    @endisset
            </ul>
        </div>
    </div>

    <div class="layui-body body-z">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            @yield('content')
        </div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © {{ config('app.name') }}
    </div>
</div>
</div>
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script type="text/javascript">
    $('body').on("click", "*[layadmin-event]", function(e) {
        var e = $(this).attr('layadmin-event');
        if (e === 'flexible') {
            if ($(window).width() > 768) {
                $('#LAY_app').toggleClass('layadmin-side-shrink');
            } else {
                $('#LAY_app').toggleClass('layadmin-side-spread-sm');
            }
        } else if (e === 'horizontal-flexible') {
            $('div.layui-header').toggleClass('nav-height');
            $('div.layui-body').toggleClass('body-z');
        }
    });
</script>
@yield('js')
</body>
</html>
