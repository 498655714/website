@extends('layouts.app')
@section('title',' | 文章列表')
@section('css')
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header layuiadmin-card-header-auto  ">
                <div class="layui-form layui-form-pane">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">文章ID</label>
                        <div class="layui-input-inline">
                            <input type="text" name="id" placeholder="请输入" value="{{old('id')}}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">作者</label>
                        <div class="layui-input-inline">
                            <input type="text" name="writer" placeholder="请输入" value="{{old('writer')}}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">标题</label>
                        <div class="layui-input-inline">
                            <input type="text" name="title" placeholder="请输入"  value="{{old('title')}}" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">文章栏目</label>
                        <div class="layui-input-inline">
                            <select name="cate_id">
                                <option value=""></option>
                                @foreach($categories as $key=>$category)
                                    <option value="{{ $category['id'] }}"  {{ old('cate_id') == $category['id'] ? 'selected' : ''}}>{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">创建时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name="created_at" class="layui-input" id="create-laydate-range-date" placeholder=" - ">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="articles_list_search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
{{--                <div class="layui-form-item">--}}
{{--                    <div class="layui-inline">--}}
{{--                        <label class="layui-form-label">推荐位</label>--}}
{{--                        <div class="layui-input-block">--}}
{{--                            @foreach($flags as $key=>$flag)--}}
{{--                                <input type="checkbox" name="flag[]" value="{{ $key }}" title="{{ $flag }}[{{ $key }}]" @if(old('flags')){{ in_array($key,old('flags')) ? 'checked' : ''}}@endif lay-skin="primary">--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                </div>
            </div>

            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-admin" data-type="batchdel">删除</button>
                    <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加</button>
                </div>
                <table id="articles" lay-filter="articles"></table>
                <script type="text/html" id="action-list">
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                </script>
            </div>
        </div>
    </div>
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}'+'/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['form','layer','table', 'laydate'],function () {
            var $ = layui.$;
            var form = layui.form;
            var layer = layui.layer
                ,laydate = layui.laydate
                ,table = layui.table;
            var pass = "{{ config('app.del_pass') }}";

            //日期范围
            laydate.render({
                elem: '#create-laydate-range-date'
                ,range: true
            });

            //列表渲染
            table.render({
                elem: '#articles' //指定原始表格元素选择器（推荐id选择器）
                ,height: 500 //容器高度
                ,method:'post'
                ,url: "{{ route('admin.articles.getData') }}" //数据接口
                ,page: true //开启分页
                ,where:{'_token':"{{csrf_token()}}"}
                ,response:{
                    statusCode:200,
                }
                ,cols: [[ //表头
                    {checkbox: true,fixed: true}
                    ,{field: 'id', title: 'ID', width:70, sort: true, fixed: 'left'}
                    ,{field: 'title', title: '文章标题',width:370, align:'center'}
                    ,{field: 'writer', title: '作者',width:80, align:'center'}
                    ,{field: 'flag_name', title: '推荐位', align:'center'}
                    ,{field: 'category', title: '类目', align:'center'}
                    ,{field: 'click', title: '点击',width:70, align:'center'}
                    ,{field: 'created_at', title: '创建时间',width:100, align:'center'}
                    ,{fixed: 'right', title:'操作',width: 220, align:'center', toolbar: '#action-list'}
                ]] //设置表头
            });

            //监听搜索
            form.on('submit(articles_list_search)', function(data){
                var field = data.field;
                //执行重载
                table.reload('articles', {
                    where: field
                });
            });
            //添加js
            @include('admin.articles._createjs');

            //监听工具条
            table.on('tool(articles)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值
                var articles_id = data.id;
                if(layEvent === 'edit'){//编辑操作
                    layer.open({
                        type: 2
                        ,title: '编辑文章'
                        ,content: "articles/"+articles_id+'/edit'
                        ,maxmin: true
                        ,offset: '20px'
                        ,area: ['1200px', '570px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'articles_edit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段
                                $.ajax({
                                    url:"articles/"+articles_id
                                    ,type:'post'
                                    ,data: field
                                    ,beforeSend:function (XMLHttpRequest) {
                                        layer.load(2);
                                    }
                                    ,success:function (res) {
                                        layer.closeAll('loading');
                                        //console.log(res);
                                        if(res.status == 'success'){
                                            layer.msg(res.data,{icon:1,time:1000});
                                            $('#articles_list_search').click();  //数据刷新
                                            layer.close(index); //关闭弹层
                                        }else {

                                            layer.msg(res.message,{icon:5,time:1000});
                                        }

                                    }
                                    ,error:function(XMLHttpRequest, textStatus, errorThrown){
                                        var res = JSON.parse(XMLHttpRequest.responseText);
                                        layer.closeAll('loading');
                                        layer.msg(res.message,{icon:5,time:2000});
                                    }
                                });
                            });

                            submit.trigger('click');
                        }
                    });
                }else if(layEvent === 'del'){//删除操作

                    layer.confirm('确定删除吗？', function(index) {
                        //执行 Ajax 后重载
                        $.ajax({
                            url:"articles/"+articles_id
                            ,type:'post'
                            ,data: {'_token':"{{csrf_token()}}",'_method':'delete'}
                            ,success:function (res) {
                                if(res.status == 'success'){
                                    layer.msg(res.data,{icon:1,time:1000});
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }else {
                                    layer.msg(res.message,{icon:5,time:1000});
                                }
                            }
                            ,error:function(XMLHttpRequest, textStatus, errorThrown){
                                var res = JSON.parse(XMLHttpRequest.responseText);
                                layer.closeAll('loading');
                                layer.msg(res.message,{icon:5,time:2000});
                            }
                        });
                    });
                }
            });

        });
    </script>
@endsection