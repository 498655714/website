//添加事件
var active = {
batchdel: function(){
    var checkStatus = table.checkStatus('comments')
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
            url:"{{ route('admin.comments.batchDestroy') }}"
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
    }
}
$('.layui-btn.layuiadmin-btn-comm').on('click', function(){
var type = $(this).data('type');
active[type] ? active[type].call(this) : '';
});