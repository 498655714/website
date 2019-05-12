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