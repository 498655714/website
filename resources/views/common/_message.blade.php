//表单提示信息
@if(count($errors)>0)
    @foreach($errors->all() as $error)
        layer.msg("{{$error}}",{icon:5});
        @break
    @endforeach
@endif

//正确提示
@if(session('success'))
    layer.msg("{{session('success')}}",{icon:6});
@endif

//错误提示
@if(session('error'))
    layer.msg("{{session('error')}}",{icon:5});
@endif

