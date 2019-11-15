@extends('layouts.app')
@section('title',' | 编辑文章')
@section('css')
@endsection
@section('content')
    <div class="layui-form layui-form-pane" lay-filter="layuiadmin-btn-admin" id="layuiadmin-btn-admin" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">文章标题  <small style="color: red">*</small></label>
            <div class="layui-input-block">
                <input type="text" name="title"  lay-verify="required" placeholder="不多于150个字符" value="{{ $article->title }}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">简略标题  <small style="color: red">*</small></label>
            <div class="layui-input-block">
                <input type="text" name="short_title" lay-verify="required" placeholder="不多于50个字符" value="{{ $article->short_title }}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推荐位</label>
            <div class="layui-input-block">
                @foreach($flags as $key=>$flag)
                    <input type="checkbox" name="flag[]" value="{{ $key }}" title="{{ $flag }}[{{ $key }}]" @if($article->flag){{ in_array($key,explode(',',$article->flag)) ? 'checked' : ''}}@endif   class="layui-input">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">TAG标签</label>
            <div class="layui-input-block">
                <input type="text" name="tag" lay-verify="" placeholder="英文 ',' 号分开，单个标签小于12字节" value="{{ $article->tag }}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">缩略图</label>
            <div class="layui-input-inline">
                <input name="thumb" lay-verify="" id="thumbSrc" placeholder="图片地址" value="{{ $article->thumb }}" class="layui-input">
            </div>
            <div class="layui-input-inline layui-btn-container" style="width: auto;">
                <button type="button" class="layui-btn layui-btn-primary" id="thumbUpload">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
            </div>
            <label class="layui-form-label">作者  <small style="color: red">*</small></label>
            <div class="layui-input-inline">
                <input name="writer" lay-verify="required"  value="{{ $article->writer }}" placeholder=""  class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章栏目  <small style="color: red">*</small></label>
            <div class="layui-input-inline">
                <select name="cate_id" lay-verify="required">
                    <option value=""></option>
                    @foreach($categories as $key=>$category)
                        <option value="{{ $category['id'] }}" {{$article->cate_id == $category['id'] ? 'selected' : ''}}>{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关键字 <small style="color: red">*</small></label>
            <div class="layui-input-block">
                <input type="text" name="keywords" lay-verify="required" placeholder="英文 ',' 号分开" value="{{ $article->keywords }}"autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容摘要 <small style="color: red">*</small> </label>
            <div class="layui-input-block">
                <textarea name="description" placeholder="请输入内容,内容要少于255个字符" class="layui-textarea">{{ $article->description }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章内容</label>
            <div class="layui-input-block">
                <textarea class="form-control" id="ckeditor1" name="content">{!! $article->content  !!}</textarea>
                <script src="{{ asset('dist/ckeditor/ckeditor.js') }}"></script>
                <script>
                    var myckeditor1 = CKEDITOR.replace( 'ckeditor1', {
                        "extraPlugins": "imgbrowse",
                        "toolbar" : [
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                            { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                            '/',
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                            { name: 'insert', items: [ 'Image', 'CodeSnippet', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                            '/',
                            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                            { name: 'others', items: [ '-' ] },
                            { name: 'about', items: [ 'About' ] }
                        ]
                    });
                    var context = document.getElementById("ckeditor1").value;
                    myckeditor1.setData(context);

                    myckeditor1.on("change",  function(evt){
                        document.getElementById("ckeditor1").innerHTML = this.getData();
                    });
                </script>

            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="put">
            <input type="button" lay-submit lay-filter="articles_edit" id="articles_edit" value="确认">
        </div>
    </div>


@section('javascript')
    <script>
        layui.config({
            base: '{{ asset("dist/layuiadmin")  }}' + '/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index','form','upload'], function(){
            var $ = layui.$
                ,form = layui.form
                ,upload = layui.upload;

            //执行实例
            var uploadInst = upload.render({
                elem: '#thumbUpload' //绑定元素
                ,url: "{{route('common.uploads')}}" //上传接口
                ,data:{'_token':"{{csrf_token()}}"}
                ,before: function(obj){
                    layer.load(2); //上传loading
                }
                ,done: function(res){
                    //上传完毕回调
                    layer.closeAll('loading'); //关闭loading
                    //console.log(res);
                    if(res.data.flag == 'success'){
                        //$("#avatar_val") = res.fiepach;
                        $("#thumbSrc").val(res.data.fiepach);
                        layer.msg(res.data.message, {icon: 1,time:2000});
                    }else{
                        layer.msg(res.message, {icon: 5,time:2000});
                    }
                }
                ,error: function(index, upload){
                    //请求异常回调
                    layer.closeAll('loading'); //关闭loading
                    //当上传失败时，你可以生成一个“重新上传”的按钮，点击该按钮时，执行 upload() 方法即可实现重新上传
                    layer.msg('网络错误，请联系管理员', {icon: 5,time:2000});
                }
            });

        });
    </script>
@endsection