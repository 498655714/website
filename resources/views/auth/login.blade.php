@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('dist/layuiadmin/style/login.css') }}" media="all">
    @endsection
@section('content')
    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>{{ config('app.name') }}</h2>
                <p>后台管理系统-登录</p>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <form action="{{route('login')}}" method="post">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="email" id="LAY-user-login-username" lay-verify="required" placeholder="邮箱" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item" style="margin-bottom: 30px;">
                    <input type="checkbox" name="remember" lay-skin="primary" title="记住密码" >
                    <a href="{{ route('password.reset',csrf_token()) }}" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
                </div>

                <div class="layui-form-item">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="login-submit">登 入</button>
                </div>
                </form>
            </div>
        </div>

        <div class="layui-trans layadmin-user-login-footer">
            <p>© 2019 </p>
        </div>
    </div>
@endsection
@section('javascript')
            <script>
                layui.config({
                    base: '{{ asset("dist/admin/layuiadmin")  }}'+'/' //静态资源所在路径
                }).extend({
                    index: 'lib/index' //主入口模块
                }).use(['form','layer'],function () {
                    var form = layui.form;
                    var $ = layui.$;
                    var layer = layui.layer;
                    @include('common._message');
                })
            </script>
@endsection
