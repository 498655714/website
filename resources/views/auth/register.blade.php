@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('dist/layuiadmin/style/login.css') }}" media="all">
@endsection
@section('content')

    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>{{ config('app.name') }}</h2>
                <p>后台管理系统</p>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-cellphone" for="LAY-user-login-cellphone"></label>
                    <input type="text" name="cellphone" id="LAY-user-login-cellphone" lay-verify="phone" placeholder="手机" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-col-xs7">
                            <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
                            <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required" placeholder="验证码" class="layui-input">
                        </div>
                        <div class="layui-col-xs5">
                            <div style="margin-left: 10px;">
                                <button type="button" class="layui-btn layui-btn-primary layui-btn-fluid" id="LAY-user-getsmscode">获取验证码</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="LAY-user-login-password" lay-verify="pass" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-repass"></label>
                    <input type="password" name="repass" id="LAY-user-login-repass" lay-verify="required" placeholder="确认密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-nickname"></label>
                    <input type="text" name="nickname" id="LAY-user-login-nickname" lay-verify="nickname" placeholder="昵称" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <input type="checkbox" name="agreement" lay-skin="primary" title="同意用户协议" checked>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-reg-submit">注 册</button>
                </div>
                <div class="layui-trans layui-form-item layadmin-user-login-other">
                    <label>社交账号注册</label>
                    <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>
                    <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>
                    <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>

                    <a href="login.html" class="layadmin-user-jump-change layadmin-link layui-hide-xs">用已有帐号登入</a>
                    <a href="login.html" class="layadmin-user-jump-change layadmin-link layui-hide-sm layui-show-xs-inline-block">登入</a>
                </div>
            </div>
        </div>

        <div class="layui-trans layadmin-user-login-footer">
            <p>© 2019 </p>
        </div>

    </div>
@endsection
