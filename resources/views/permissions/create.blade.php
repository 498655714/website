@extends('layouts.app')
@section('title',' | 创建权限')
@section('css')
@section('content')

    <div class="layui-form layui-form-pane" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" placeholder="请输入名称"  class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">中文名称</label>
            <div class="layui-input-block">
                <input type="text" name="chinese_name" lay-verify="required" placeholder="请输入中文名称"  class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">认证类型</label>
            <div class="layui-input-block">
                <input type="text" name="guard_name" lay-verify="required" placeholder="请输入组名称"  class="layui-input">
                <i class="layui-icon layui-icon-tips" style="font-size: 15px; color: #FF3A52;">例：</i>
                <label style="font-size: 15px; color: #FF3A52;">web用来认证前台用户、admin用来认证后台用户、api用来认证为第三方提供的接口</label>
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">赋予角色权限</label>
            <div class="layui-input-block">
                @foreach($roles as $key => $role)
{{--                    默认第一个为超级管理员角色,超管拥有所有权限      --}}
                    @if($key === 0) @continue @endif
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" title="{{ $role->chinese_name }}"   class="layui-input">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="button" lay-submit lay-filter="permissions_add" id="permissions_add" value="确认">
        </div>
    </div>
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin") }}'+'/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index','form'],function () {
            var $ = layui.$;
            var form = layui.form;
        })
    </script>
@stop