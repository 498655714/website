//添加事件
var active = {
batchdel: function(){
    var checkStatus = table.checkStatus('articles')
     ,checkData = checkStatus.data; //得到选中的数据
    if(checkData.length === 0){
        return layer.msg('请选择数据');
    }

    layer.confirm('确定删除吗？', function(index) {
        var ids = [];
        for(var i in checkData){
            for(var j in checkData[i]){
                if(j == 'id'){
                    ids[i] = checkData[i]['id'];
                    break;
                }
            }
        }
        $.ajax({
            url:"{{ route('admin.articles.batchDestroy') }}"
            ,type:'post'
            ,data: {'ids':ids,'_token':"{{csrf_token()}}"}
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
},
add: function(){
    layer.open({
        type: 2
        ,title: '添加文章'
        ,content: '{{ route('admin.articles.create') }}'
        ,area: ['1200px', '570px']
        ,maxmin:true
        ,offset: '20px'
        ,btn: ['确定', '取消']
        ,yes: function(index, layero){
            var iframeWindow = window['layui-layer-iframe'+ index]
            ,submitID = 'articles_add'
            ,submit = layero.find('iframe').contents().find('#'+ submitID);

            //监听提交
            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                var field = data.field; //获取提交的字段

                //提交 Ajax 成功后，静态更新表格中的数据
                $.ajax({
                    url:"{{ route('admin.articles.store') }}"
                    ,type:'post'
                    ,data: field
                    ,beforeSend:function (XMLHttpRequest) {
                        layer.load(2);
                    }
                    ,success:function (res) {
                        layer.closeAll('loading');
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
}
}
$('.layui-btn.layuiadmin-btn-admin').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
});