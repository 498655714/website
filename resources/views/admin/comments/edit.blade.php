@extends('layouts.app')
@section('title',' | 评论列表')
@section('css')
@endsection
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-comment" id="layuiadmin-form-comment" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">评论所属文章栏目</label>
            <div class="layui-input-inline">
                    {{ $cate_name }}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">评论所属文章</label>
            <div class="layui-input-inline">
                {{ $article_title }}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">评论人</label>
            <div class="layui-input-inline">
                {{$comment->reviewer_name}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">IP地址</label>
            <div class="layui-input-inline">
                {{$comment->ip}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">评论内容</label>
            <div class="layui-input-block">
                <textarea name="context" lay-verify="required" placeholder="请输入" class="layui-textarea">{{$comment->context}}</textarea>
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">
                <input type="button" lay-submit lay-filter="comments_edit" id="comments_edit" value="确认" class="layui-btn">
            </div>
        </div>
    </div>
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}'+'/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['form','layer','table'],function () {
            var $ = layui.$;
            var form = layui.form;
            var layer = layui.layer
                ,table = layui.table;

        });
    </script>
@endsection