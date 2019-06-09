@extends('layouts.app')
@section('title',' | 密码重置')
@section('css')
    <link rel="stylesheet" href="{{ asset('dist/layuiadmin/style/login.css') }}" media="all">
@endsection
@section('content')
    <div class="layadmin-user-login layadmin-user-display-show"  style="display: none;">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>{{ config('app.name') }}</h2>
                <p>后台管理系统-密码重置</p>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <form action="{{ route('admin.password.reset') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                        <input type="password" name="password" id="password" lay-verify="required|pass" placeholder="新密码" class="layui-input">
                    </div>
                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-repass"></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" lay-verify="required|pass" placeholder="确认密码" class="layui-input">
                    </div>
                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-release" for="LAY-user-login-cellphone"></label>
                        <input type="text" name="email" id="email" lay-verify="required|email" placeholder="请输入注册时的邮箱" value="{{ old('email') }}" class="layui-input">
                    </div>

                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="forget-submit">重置密码</button>
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
        }).use(['layer'],function () {
            var layer = layui.layer;
            @include('common._message')
        })
    </script>
@endsection
