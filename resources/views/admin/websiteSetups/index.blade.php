@extends('layouts.app')
@section('title',' | 站点基本信息设置')
@section('css')
@endsection
@section('content')

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">网站基本信息设置</div>
                    <div class="layui-card-body">
                            <div class="layui-form layui-form-pane" lay-filter="">
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 140px;">网站名称</label>
                                    <div class="layui-input-inline" style="width: 1000px;">
                                        <input type="text" name="site_name" lay-verify="required" value="{{ $setups['site_name'] }}"
                                               class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 140px;">网站域名</label>
                                    <div class="layui-input-inline" style="width: 1000px;">
                                        <input type="text" name="domain" lay-verify="url"
                                               value="{{ $setups['domain'] }}" class="layui-input">
                                    </div>
                                </div>
{{--                                <div class="layui-form-item">--}}
{{--                                    <label class="layui-form-label" style="width: 140px;">缓存时间</label>--}}
{{--                                    <div class="layui-input-inline" style="width: 80px;">--}}
{{--                                        <input type="text" name="cache" lay-verify="number"--}}
{{--                                               value="{{ $setups['cache'] }}" class="layui-input">--}}
{{--                                    </div>--}}
{{--                                    <div class="layui-input-inline layui-input-company">分钟</div>--}}
{{--                                    <div class="layui-form-mid layui-word-aux">本地开发一般推荐设置为 0，线上环境建议设置为 10。</div>--}}
{{--                                </div>--}}
{{--                                <div class="layui-form-item">--}}
{{--                                    <label class="layui-form-label" style="width: 140px;">最大文件上传</label>--}}
{{--                                    <div class="layui-input-inline" style="width: 80px;">--}}
{{--                                        <input type="text" name="max_upload" lay-verify="number"--}}
{{--                                               value="{{ $setups['max_upload'] }}" class="layui-input">--}}
{{--                                    </div>--}}
{{--                                    <div class="layui-input-inline layui-input-company">KB</div>--}}
{{--                                    <div class="layui-form-mid layui-word-aux">提示：1 M = 1024 KB</div>--}}
{{--                                </div>--}}
{{--                                <div class="layui-form-item">--}}
{{--                                    <label class="layui-form-label" style="width: 140px;">上传文件类型</label>--}}
{{--                                    <div class="layui-input-inline" style="width: 1000px;">--}}
{{--                                        <input type="text" name="ext_upload"   lay-verify="required" value="{{ $setups['ext_upload'] }}"--}}
{{--                                               class="layui-input">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">首页标题</label>
                                    <div class="layui-input-block">
                                        <textarea name="title"  lay-verify="required" class="layui-textarea">{{ $setups['title'] }}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">META关键词</label>
                                    <div class="layui-input-block">
                                        <textarea name="keywords"  lay-verify="required" class="layui-textarea"
                                                  placeholder="多个关键词用英文状态 , 号分割">{{ $setups['keywords'] }}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">META描述</label>
                                    <div class="layui-input-block">
                                        <textarea name="description"  lay-verify="required"
                                                  class="layui-textarea">{{ $setups['description'] }}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">版权信息</label>
                                    <div class="layui-input-block">
                                        <textarea name="copyright"  lay-verify="required"
                                                  class="layui-textarea">{{ $setups['copyright'] }}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="layui-btn" lay-submit lay-filter="set_website">确认保存</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}' + '/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['form', 'layer'], function () {
            var form = layui.form, layer = layui.layer, $ = layui.$;
            form.on('submit(set_website)', function(data){
                var field = data.field;
                $.ajax({
                    url:"{{ route('admin.websiteSetup.store') }}"
                    ,type:'post'
                    ,data: field
                    ,beforeSend:function (XMLHttpRequest) {
                        layer.load(2);
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