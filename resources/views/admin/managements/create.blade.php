@extends('layouts.app')
@section('title',' | 添加后台管理用户')
@section('css')
@endsection
@section('content')
    <div class="layui-form  layui-form-pane" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 120px">登录名</label>
            <div class="layui-input-inline" style="width: 400px">
                <input type="text" name="username" lay-verify="required" placeholder="请输入登录名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 120px">用户名称</label>
            <div class="layui-input-inline" style="width: 400px">
                <input type="text" name="name" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 120px">手机</label>
            <div class="layui-input-inline" style="width: 400px">
                <input type="text" name="phone" lay-verify="phone" placeholder="请输入号码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 120px">邮箱</label>
            <div class="layui-input-inline" style="width: 400px">
                <input type="text" name="email" lay-verify="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label" style="width: 120px">赋予用户角色</label>
            <div class="layui-input-block" style="width: 400px">
                @foreach($roles as $key => $role)
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" title="{{ $role->chinese_name }}"   class="layui-input">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="LAY-user-front-submit" id="managements_add" value="确认">
        </div>
    </div>

@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}' + '/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index'], function(){
            var $ = layui.$
                ,form = layui.form;
        });
    </script>
@endsection