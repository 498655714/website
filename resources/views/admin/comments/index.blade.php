@extends('layouts.app')
@section('title',' | 评论列表')
@section('css')
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
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
                        <label class="layui-form-label">用户IP</label>
                        <div class="layui-input-inline">
                            <input type="text" name="ip" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-comm" data-type="reload" lay-submit lay-filter="comments_list_search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-comm" data-type="batchdel">删除</button>
                </div>
                <table id="comments" lay-filter="comments"></table>
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
        }).use(['form','layer','table'],function () {
            var $ = layui.$;
            var form = layui.form;
            var layer = layui.layer
                ,table = layui.table;
            var pass = "{{ config('app.del_pass') }}";


            //列表渲染
            table.render({
                elem: '#comments' //指定原始表格元素选择器（推荐id选择器）
                ,height: 500 //容器高度
                ,method:'post'
                ,url: "{{ route('admin.comments.getData') }}" //数据接口
                ,page: true //开启分页
                ,where:{'_token':"{{csrf_token()}}"}
                ,response:{
                    statusCode:200,
                }
                ,cols: [[ //表头
                    {checkbox: true,fixed: true}
                    ,{field: 'id', title: 'ID', width:70, sort: true, fixed: 'left'}
                    ,{field: 'article_title', title: '文章标题',width:180, align:'center'}
                    ,{field: 'context', title: '评论内容',width:370}
                    ,{field: 'reviewer_name', title: '评论人', align:'center'}
                    ,{field: 'ip', title: '评论人IP', align:'center'}
                    ,{field: 'created_at', title: '创建时间', align:'center'}
                    ,{fixed: 'right', title:'操作',width: 230, align:'center', toolbar: '#action-list'}
                ]] //设置表头
            });

            //监听搜索
            form.on('submit(comments_list_search)', function(data){
                var field = data.field;
                //执行重载
                table.reload('comments', {
                    where: field
                });
            });
            //删除js
            @include('admin.comments._deletejs');

            //监听工具条
            table.on('tool(comments)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值
                var comments_id = data.id;
                if(layEvent === 'edit'){//编辑操作
                    layer.open({
                        type: 2
                        ,title: '编辑评论'
                        ,content: "comments/"+comments_id+'/edit'
                        ,maxmin: true
                        ,offset: '20px'
                        ,area: ['1200px', '570px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'comments_edit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段
                                $.ajax({
                                    url:"comments/"+comments_id
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
                                            $('#comments_list_search').click();  //数据刷新
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
                            url:"comments/"+comments_id
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