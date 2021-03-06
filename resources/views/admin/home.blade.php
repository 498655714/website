@extends('layouts.app')
@section('title',' | 首页')
@section('css')
@endsection
@section('content')

    <div id="LAY_app">
        <div class="layui-layout layui-layout-admin">
            <div class="layui-header">
                <!-- 头部区域 -->
                <ul class="layui-nav layui-layout-left">
                    <li class="layui-nav-item layadmin-flexible" lay-unselect>
                        <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                            <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="{{ url('/') }}" target="_blank" title="前台">
                            <i class="layui-icon layui-icon-website"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;" layadmin-event="refresh" title="刷新">
                            <i class="layui-icon layui-icon-refresh-3"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search.html?keywords=">
                    </li>
                </ul>
                <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                    <li class="layui-nav-item" lay-unselect>
                        <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
                            <i class="layui-icon layui-icon-notice"></i>

                            <!-- 如果有新消息，则显示小圆点 -->
                            <span class="layui-badge-dot"></span>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="theme">
                            <i class="layui-icon layui-icon-theme"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="note">
                            <i class="layui-icon layui-icon-note"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="fullscreen">
                            <i class="layui-icon layui-icon-screen-full"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;">
                            <cite>{{ Auth::user()->name }}</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{ route('admin.personal.index') }}">基本资料</a></dd>
                            <dd><a lay-href="{{ route('admin.personal.setpass') }}">修改密码</a></dd>
                            <hr>
                            <form action="{{ route('admin.logout') }}" method="post" id="logout">
                                {{ csrf_field() }}
                                <dd  style="text-align: center;"><a href="javascript:;" onclick="click_logout()">退出</a></dd>{{-- layadmin-event="logout"--}}
                            </form>
                        </dl>
                    </li>

                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" ><i class="layui-icon layui-icon-more-vertical"></i></a>
                    </li>
                    <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                        <a href="javascript:;" ><i class="layui-icon layui-icon-more-vertical"></i></a>
                    </li>
                </ul>
            </div>

            <!-- 侧边菜单 -->
            <div class="layui-side layui-side-menu">
                <div class="layui-side-scroll">
                    <div class="layui-logo" lay-href="{{ route('admin.console') }}">
                        <span>{{ config('app.name') }}</span>
                    </div>

                    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                        <li class="layui-nav-item layui-nav-itemed">
                            <a href="javascript:;" lay-tips="主页" lay-direction="2">
                                <i class="layui-icon layui-icon-home"></i>
                                <cite>主页</cite>
                            </a>
                            <dl class="layui-nav-child">
                                <dd data-name="console" class="layui-this">
                                    <a lay-href="{{ route('admin.console') }}">控制台</a>
                                </dd>
                            </dl>
                        </li>
                        <li data-name="app" class="layui-nav-item">
                            <a href="javascript:;" lay-tips="应用" lay-direction="2">
                                <i class="layui-icon layui-icon-app"></i>
                                <cite>应用</cite>
                            </a>
                            <dl class="layui-nav-child">

                                <dd data-name="content">
                                    <a href="javascript:;">内容系统</a>
                                    <dl class="layui-nav-child">
                                        <dd data-name="list"><a lay-href="{{ route('admin.articles.index') }}">文章列表</a></dd>
                                        <dd data-name="tags"><a lay-href="{{ route('admin.categories.index') }}">分类管理</a></dd>
                                        <dd data-name="comment"><a lay-href="{{ route('admin.comments.index') }}">评论管理</a></dd>
                                    </dl>
                                </dd>
                                <dd data-name="forum">
                                    <a href="javascript:;">社区系统</a>
                                    <dl class="layui-nav-child">
                                        <dd data-name="list"><a lay-href="app/forum/list.html">帖子列表</a></dd>
                                        <dd data-name="replys"><a lay-href="app/forum/replys.html">回帖列表</a></dd>
                                    </dl>
                                </dd>
                                <dd>
                                    <a lay-href="app/message/index.html">消息中心</a>
                                </dd>
                                <dd data-name="workorder">
                                    <a lay-href="app/workorder/list.html">工单系统</a>
                                </dd>
                            </dl>
                        </li>

                        <li data-name="user" class="layui-nav-item">
                            <a href="javascript:;" lay-tips="用户" lay-direction="2">
                                <i class="layui-icon layui-icon-user"></i>
                                <cite>用户</cite>
                            </a>
                            <dl class="layui-nav-child">
{{--                                <dd>--}}
{{--                                    <a lay-href="{{ route('user.managements.index') }}">网站用户</a>--}}
{{--                                </dd>--}}
                                <dd>
                                    <a lay-href="{{ route('admin.managements.index') }}">后台管理员</a>
                                </dd>
                                <dd>
                                    <a lay-href="{{ route('admin.permissions.index') }}">权限管理</a>
                                </dd>
                                <dd>
                                    <a lay-href="{{ route('admin.roles.index') }}">角色管理</a>
                                </dd>
                            </dl>
                        </li>
                        <li data-name="set" class="layui-nav-item">
                            <a href="javascript:;" lay-tips="设置" lay-direction="2">
                                <i class="layui-icon layui-icon-set"></i>
                                <cite>设置</cite>
                            </a>
                            <dl class="layui-nav-child">
                                <dd class="layui-nav-itemed">
                                    <a href="javascript:;">系统设置</a>
                                    <dl class="layui-nav-child">
                                        <dd><a lay-href="{{ route('admin.websiteSetup.index') }}">网站设置</a></dd>
                                    </dl>
                                </dd>
                                <dd class="layui-nav-itemed">
                                    <a href="javascript:;">我的设置</a>
                                    <dl class="layui-nav-child">
                                        <dd><a lay-href="{{ route('admin.personal.index') }}">基本资料</a></dd>
                                        <dd><a lay-href="{{ route('admin.personal.setpass') }}">修改密码</a></dd>
                                    </dl>
                                </dd>
                            </dl>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 页面标签 -->
            <div class="layadmin-pagetabs" id="LAY_app_tabs">
                <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                        <li class="layui-nav-item" lay-unselect>
                            <a href="javascript:;"></a>
                            <dl class="layui-nav-child layui-anim-fadein">
                                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
                <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                    <ul class="layui-tab-title" id="LAY_app_tabsheader">
                        <li lay-id="{{ route('admin.console') }}" lay-attr="{{ route('admin.console') }}" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                    </ul>
                </div>
            </div>


            <!-- 主体内容 -->
            <div class="layui-body" id="LAY_app_body">
                <div class="layadmin-tabsbody-item layui-show">
                    <iframe src="{{ route('admin.console') }}" frameborder="0" class="layadmin-iframe"></iframe>
                </div>
            </div>

            <!-- 辅助元素，一般用于移动设备下遮罩 -->
            <div class="layadmin-body-shade" layadmin-event="shade"></div>
        </div>
    </div>

@endsection
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}'+'/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index','layer'],function () {
            var $ = layui.$;
            var layer = layui.layer;
            @include('common._message');

        })
        function click_logout(){
            document.getElementById('logout').submit();
        }
    </script>
@endsection