@extends('layouts.app')
@section('title',' | 分类列表')
@section('css')
@endsection
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    {{--                <button class="layui-btn layuiadmin-btn-admin" data-type="batchdel">删除</button>--}}
                    <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加顶级分类</button>
                    <button class="layui-btn" id="btn-expand">全部展开</button>
                    <button class="layui-btn" id="btn-fold">全部折叠</button>
                    <button class="layui-btn" id="btn-refresh">刷新表格</button>
                </div>
                <table id="categories" lay-filter="categories"></table>
                <script type="text/html" id="action-list">
                    <a class="layui-btn layui-btn-success layui-btn-xs" lay-event="add"><i class="layui-icon layui-icon-edit"></i>添加子类</a>
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                </script>
            </div>
        </div>
    </div>
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin/modules/")  }}'+'/' //静态资源所在路径
        }).extend({
            treetable: 'treetable-lay/treetable'
        }).use(['layer','table','treetable'],function () {
            var $ = layui.$;
            var layer = layui.layer
                ,table = layui.table
                ,treetable = layui.treetable;
            //列表渲染
            var renderTable = function () {
                //layer.load(2);
                treetable.render({
                    treeColIndex: 1,          // 树形图标显示在第几列
                    treeSpid: 0,             // 顶级id
                    treeIdName: 'id',       // id
                    treePidName: 'pid',     // 父级id名称
                    treeDefaultClose: false,   // 默认是否折叠
                    treeLinkage: true,        // 是否打开全部子类
                    height: 500 //容器高度
                    ,elem: '#categories' //指定原始表格元素选择器（推荐id选择器）
                    ,url: "{{ route('admin.categories.getData') }}" //数据接口
                    ,page: false //不能开启分页功能
                    ,cols: [[ //表头
                        {type: 'numbers'}
                        ,{field: 'name', title: '名称',width:350}
                        ,{field: 'id', title: 'ID', width:80}
                        ,{field: 'pid', title: '父级ID', align:'center'}
                        ,{field: 'sort', title: '排序',align:'center'}
                        ,{templet:'#action-list', title: '操作',width: 320, align:'center'}
                    ]] //设置表头
                    ,done: function () {
                        layer.closeAll('loading');
                    }
                });
            }

            renderTable();
            //触发三个button按钮
            $('#btn-expand').click(function () {
                treetable.expandAll('#categories');
            });

            $('#btn-fold').click(function () {
                treetable.foldAll('#categories');
            });

            $('#btn-refresh').click(function () {
                renderTable();
            });
            //添加权限js
            @include('admin.categories._createjs');

            //监听工具条
            table.on('tool(categories)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值
                var categories_id = data.id;
                if(layEvent === 'edit'){//编辑操作
                    layer.open({
                        type: 2
                        ,title: '编辑分类'
                        ,content: "categories/"+categories_id+'/edit'
                        ,maxmin: true
                        ,area: ['360px', '300px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'categories_edit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                $.ajax({
                                    url:"categories/"+categories_id
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
                                            renderTable();
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
                    layer.confirm('是否删除该分类？删除会将子类一并删除！',{
                        btn: ['确定','取消']
                    }, function(){
                        //执行 Ajax 后重载
                        $.ajax({
                            url:"categories/"+categories_id
                            ,type:'post'
                            ,data: {'_token':"{{csrf_token()}}",'_method':'delete'}
                            ,success:function (res) {
                                if(res.status == 'success'){
                                    layer.msg(res.data,{icon:1,time:1000});
                                    renderTable();
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

                    }, function() {

                    });
                }else if(layEvent === 'add'){//添加子类
                    layer.open({
                        type: 2
                        ,title: '添加子类'
                        ,content: "{{ route('admin.categories.create') }}"
                        ,maxmin: true
                        ,area: ['360px', '300px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'categories_add'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段
                                field.pid = categories_id;
                                $.ajax({
                                    url:"{{ route('admin.categories.store') }}"
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
                                            renderTable();
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
                }
            });

        });
    </script>
@endsection