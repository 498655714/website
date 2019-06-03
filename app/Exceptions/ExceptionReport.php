<?php


namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionReport
{
    use ApiResponse;
    /**
     * @var Exception
     */
    public $exception;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    public $report;


    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param Exception $exception
     *
     */
    function __construct(Request $request,Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @var array
     */
    public $doReport = [
        AuthenticationException::class=>['未授权',401],
        ModelNotFoundException::class=>['该模型未找到',404],
        AuthorizationException::class=>['没有此权限',403],
        ValidationException::class=>[],
        UnauthorizedHttpException::class=>['未登录或登录状态失效',422],
        NotFoundHttpException::class=>['没有找到该页面',404],
        MethodNotAllowedHttpException::class=>['访问方法不正确',405],
        QueryException::class=>['参数错误',401],
    ];

    /**
     * @param $className
     * @param callable $callback
     */
    public function register($className,callable $callback){
        $this->doReport[$className] = $callback;
    }

    /**
     * @return bool
     */
    public function shouldReturn(){
        foreach(array_keys($this->doReport) as $report){
            if($this->exception instanceof $report){
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    /**
     * @param Exception $e
     * @return ExceptionReport
     */
    public static function make(Exception $e){
        return new static(\request(),$e);
    }

    /**
     * @return mixed
     */
    public function report(){
        if($this->exception instanceof ValidationException){
            $error = array_first($this->exception->errors());
//            return $this->failed(array_first($error),$this->exception->status);
            return $this->failed(array_first($error),200);//http 请求状态：200 具体错误信息返回前端展示
        }

        $message = $this->doReport[$this->report];
        return $this->failed($message[0],$message[1]);
    }

    /**
     * @return mixed
     */
    public function prodReport(){
        return $this->failed('服务器错误','500');
    }
}