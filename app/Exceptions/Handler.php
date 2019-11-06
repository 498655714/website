<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'oldPassword',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //ajax请求异常处理
        if($request->ajax()){
            $reporter = ExceptionReport::make($exception);
            if($reporter->shouldReturn()){
                return $reporter->report();
            }
            if(env('APP_DEBUG')){
                return parent::render($request,$exception);
            }else{
                return $reporter->prodReport();
            }
        }

        //角色对应权限验证
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->view('error.' . 403, [], 403);
        }
        //非ajax请求异常处理
//        if (!($exception instanceof AuthenticationException)) {
//            $code = $exception->getStatusCode();
//            if (view()->exists('errors.' .$code )) {
//                return response()->view('error.' . $code, [], $code);
//            }else{
//                return response()->view('error.error', [], $code);
//            }
//        }

        return parent::render($request, $exception);
    }

    /**
     * 重写未登录认证方法，跳转对应守卫的登录页面
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()){
            return response()->json(['message' => $exception->getMessage()], 401);
        }else{
            echo  in_array('admin', $exception->guards())?  "<script>top.location.href='/admin/login'</script>" : "<script>top.location.href='/login'</script>";
        }
    }
}
