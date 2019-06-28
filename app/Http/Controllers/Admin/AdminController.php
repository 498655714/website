<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }
    //后台管理用户管理



    //个人设置页面展示
    public function personalIndex(){
        return view('admin.personal.index');
    }

    //密码修改页面
    public function setPassword(){
        return view('admin.personal.setpass');
    }

    //密码修改
    public function setPasswordUpdate(Request $request){
        $this->validate($request,[
            'oldPassword'=>'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $old_password  = $request->oldPassword;
        $new_password  = $request->password;

        $user = Auth::user();//检测当前登录用户
        $curr_password = $user->getAuthPassword();
        if(Hash::check($old_password,$curr_password)){
            $user->update(['password'=>bcrypt($new_password)]);
        }else{
            return $this->failed('原始密码错误',200);
        }

       return $this->success('修改成功');
    }
}
