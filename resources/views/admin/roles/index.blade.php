@extends('layouts.app')
@section('title',' | 角色列表')
@section('css')
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">角色名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">中文名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="chinese_name" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">认证类型</label>
                        <div class="layui-input-block">
                            <input type="text" name="guard_name" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button id="roles_list_search" class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="roles_list_search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加新角色</button>
                </div>

                <table id="role" lay-filter="role"></table>
                <script type="text/html" id="action-list">
                    @{{#  if(d.id == 1){ }}
                    <a class="layui-btn layui-btn-disabled layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-disabled layui-btn-xs"><i class="layui-icon layui-icon-delete"></i>删除</a>
                    @{{#  } else { }}
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                    @{{#  } }}
                </script>
            </div>
        </div>
    </div>

@endsection
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
                elem: '#role' //指定原始表格元素选择器（推荐id选择器）
                ,height: 500 //容器高度
                ,method:'post'
                ,url: "{{ route('admin.roles.getData') }}" //数据接口
                ,page: true //开启分页
                ,where:{'_token':"{{csrf_token()}}"}
                ,response:{
                    statusCode:200,
                }
                ,cols: [[ //表头
                    {checkbox: true,fixed: true}
                    ,{field: 'id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'name', title: '名称'}
                    ,{field: 'chinese_name', title: '中文名称'}
                    ,{field: 'guard_name', title: '认证类型'}
                    ,{field: 'created_at', title: '创建时间'}
                    ,{fixed: 'right', width: 220, align:'center', toolbar: '#action-list'}
                ]] //设置表头
            });

            //监听搜索
            form.on('submit(roles_list_search)', function(data){
                var field = data.field;
                //执行重载
                table.reload('role', {
                    where: field
                });
            });
            //添加权限js
            @include('admin.roles._createjs');

            //监听工具条
            table.on('tool(role)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值
                var roles_id = data.id;
                if(layEvent === 'edit'){//编辑操作
                    layer.open({
                        type: 2
                        ,title: '编辑角色'
                        ,content: "roles/"+roles_id+'/edit'
                        ,maxmin: true
                        ,area: ['760px', '550px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'roles_edit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                $.ajax({
                                    url:"roles/"+roles_id
                                    ,type:'post'
                                    ,data: field
                                    ,beforeSend:function (XMLHttpRequest) {
                                        layer.load(2);
                                    }
                                    ,success:function (res) {
                                        layer.closeAll('loading');
                                        if(res.status == 'success'){
                                            layer.msg(res.data,{icon:1,time:1000});
                                            $('#roles_list_search').click();  //数据刷新
                                            layer.close(index); //关闭弹层
                                        }else {
                                            layer.msg(res.message,{icon:5,time:2000});
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
                    layer.prompt({
                        formType: 1
                        ,title: '敏感操作，请验证口令'
                    }, function(value, index){
                        layer.close(index);
                        if(value == pass){
                            layer.confirm('确定删除吗？', function(index) {
                                //执行 Ajax 后重载
                                $.ajax({
                                    url:"roles/"+roles_id
                                    ,type:'post'
                                    ,data: {'_token':"{{csrf_token()}}",'_method':'delete'}
                                    ,success:function (res) {
                                        if(res.status == 'success'){
                                            layer.msg(res.data,{icon:1,time:1000});
                                            obj.del(); //删除对应行（tr）的DOM结构
                                        }else {
                                            layer.msg(res.message,{icon:5,time:2000});
                                        }

                                    }
                                    ,error:function(XMLHttpRequest, textStatus, errorThrown){
                                        var res = JSON.parse(XMLHttpRequest.responseText);
                                        layer.closeAll('loading');
                                        layer.msg(res.message,{icon:5,time:2000});
                                    }
                                });
                            });
                        }else{
                            layer.msg('口令错误',{icon: 5,time:1000});
                        }

                    });
                }
            });

        });
    </script>
@endsection