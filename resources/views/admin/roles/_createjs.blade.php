//添加事件
var active = {
add: function(){
layer.open({
type: 2
,title: '创建角色'
,content: '{{ route('admin.roles.create') }}'
,area: ['760px', '550px']
,btn: ['确定', '取消']
,yes: function(index, layero){
var iframeWindow = window['layui-layer-iframe'+ index]
,submitID = 'roles_add'
,submit = layero.find('iframe').contents().find('#'+ submitID);

//监听提交
iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
var field = data.field; //获取提交的字段

$.ajax({
url:"{{ route('admin.roles.store') }}"
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
}
}
$('.layui-btn.layuiadmin-btn-admin').on('click', function(){
var type = $(this).data('type');
active[type] ? active[type].call(this) : '';
});