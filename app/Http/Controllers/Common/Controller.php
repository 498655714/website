<?php


namespace App\Http\Controllers\Common;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponse;

    //递归处理栏目列表
    public function recursiveArr($arr,$pid=0,$pre_pid=0,$prefix=''){
        static $list = [];
        foreach($arr as $key => $value){
            if($value['pid'] == $pid){
                if($pre_pid == $pid){
                    $prefix .= '';
                }else{
                    $pre_pid = $pid;
                    $prefix .= '—';
                }
                if($pid === 0){
                    $value['name'];
                }else{
                    $value['name'] = $prefix.$value['name'];
                }
                $list[] = $value;
                unset($arr[$key]);
                $this->recursiveArr($arr,$value['id'],$pre_pid,$prefix);
            }
        }

        return $list;
    }

    /**
     * 获取当前登录用户的 guard
     * 注意：该方法如果不同guard，使用了相同provider则无法正确判断当前guard
     * 如果出现上述问题则解决方案为： 在 Illuminate\Auth\Middleware\Authenticate中
     * authenticate方法 设置 $this->setDefaultDriver($guard);
     * 并通过 getDefaultDriver() 即可获得 当前的guard
     */
    public function getCurrentGuard(){
        $guards = array_keys(config('auth.guards'));
        $current_guards = '';
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $current_guards = $guard;
                break;
            }
        }
        return $current_guards;
    }
}