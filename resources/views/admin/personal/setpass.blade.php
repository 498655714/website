
@extends('layouts.app')
@section('title',' | 修改密码')
@section('css')
@endsection
@section('content')

    <div class="layui-fluid layui-form-pane">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">修改密码</div>
                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">当前密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="oldPassword" lay-verify="required" lay-verType="tips" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password" lay-verify="pass" lay-verType="tips" autocomplete="off" id="LAY_password" class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">确认新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password_confirmation" lay-verify="repass" lay-verType="tips" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button class="layui-btn" lay-submit lay-filter="setmypass">确认修改</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}' + '/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['form', 'layer'], function () {
            var form = layui.form, layer = layui.layer, $ = layui.$;
            form.on('submit(setmypass)', function(data){
                var field = data.field;
                $.ajax({
                    url:"{{ route('admin.personal.setpass') }}"
                    ,type:'post'
                    ,data: field
                    ,beforeSend:function (XMLHttpRequest) {
                        layer.load();
                    }
                    ,success:function (res) {
                        layer.closeAll('loading');
                        if(res.status == 'success'){
                            layer.msg(res.data,{icon:1,time:2000},function () {
                                location.reload();
                            });
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
        });
    </script>
@endsection