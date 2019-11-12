@extends('layouts.app')
@section('title',' | 添加分类')
@section('css')
@endsection
@section('content')
    <div class="layui-form   layui-form-pane" lay-filter="layuiadmin-form-tags" id="layuiadmin-app-form-tags" style="padding-top: 30px; text-align: center;">
        <div class="layui-form-item">
            <label class="layui-form-label">分类名</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" placeholder="请输入..." autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" lay-verify="required" placeholder="请输入..." autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="hidden"  name="pid" value="0">
            <input type="hidden"  name="_token" value="{{ csrf_token() }}">
            <input type="button" lay-submit lay-filter="categories_add" id="categories_add" value="确认">
        </div>
    </div>

@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}' + '/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index','form'], function(){
            var $ = layui.$
                ,form = layui.form;
        });
    </script>
@endsection