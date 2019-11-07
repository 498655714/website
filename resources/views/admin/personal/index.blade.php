
@extends('layouts.app')
@section('title',' | 个人信息设置')
@section('css')
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">设置我的资料</div>
                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">我的角色</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="my_roles" value="{{  $admin_roles  }}" disabled class="layui-input">

                                </div>
                                <div class="layui-form-mid layui-word-aux">当前角色不可更改为其它角色</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">用户名</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="username" value="{{ $admin->username }}" disabled class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">不可修改。一般用于后台登入名</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">昵称</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="name" value="{{ $admin->name }}" lay-verify="nickname" autocomplete="off" placeholder="请输入昵称" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="sex" value="男" title="男" @if($admin->sex == '男') checked @endif>
                                    <input type="radio" name="sex" value="女" title="女"  @if($admin->sex == '女') checked @endif >
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">头像</label>
                                <div class="layui-input-inline">
                                    <input name="avatar" lay-verify="required" id="LAY_avatarSrc" placeholder="图片地址" value="{{ $admin->avatar }}" class="layui-input">
                                </div>
                                <div class="layui-input-inline layui-btn-container" style="width: auto;">
                                    <button type="button" class="layui-btn layui-btn-primary" id="LAY_avatarUpload">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
                                    <button class="layui-btn layui-btn-primary" layadmin-event="avartatPreview">查看图片</button >
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手机</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="phone" value="{{ $admin->phone }}" lay-verify="phone" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email" value="{{ $admin->email }}" lay-verify="email" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">备注</label>
                                <div class="layui-input-block">
                                    <textarea name="remarks" placeholder="您可以备注一下其他信息" class="layui-textarea">{{ $admin->remarks }}</textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <input type="hidden" name="id" value="{{ $admin->id }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button class="layui-btn" lay-submit lay-filter="setmyinfo">确认修改</button>
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
            form.on('submit(setmyinfo)', function(data){
                var field = data.field;
                $.ajax({
                    url:"{{ route('admin.personal.index') }}"
                    ,type:'post'
                    ,data: field
                    ,beforeSend:function (XMLHttpRequest) {
                        layer.load();
                    }
                    ,success:function (res) {
                        layer.closeAll('loading');
                        if(res.status == 'success'){
                            layer.msg(res.data,{icon:1,time:2000});
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