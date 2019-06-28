<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
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

}
