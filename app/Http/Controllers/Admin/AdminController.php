<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }
    /**
     * 显示后台管理用户列表.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $roles = Role::get();
        return view('admin.managements.index')->with('roles',$roles);
    }

    /**
     * 显示创建后台管理用户表单.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        return view('admin.managements.create')->with('roles',$roles);
    }

    /**
     * 保存新创建的后台管理用户.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'username'=>'required|string|max:40',
            'name'=>'required|string|max:40',
            'phone'=>'required|int|unique:users',
            'email'=>'required|string|email|max:255|unique:users',
        ]);
        $name = $request['name'];
        $username = $request['username'];
        $phone = $request['phone'];
        $email = $request['email'];
        $admin = new Admin();
        $admin->name = $name;
        $admin->username = $username;
        $admin->phone = $phone;
        $admin->email = $email;
        $admin->password =  Hash::make('123456');//默认密码123456

        $admin = $admin->save();

        $roles = $request['roles']; // 获取输入的角色字段
        // 检查是否某个角色被选中
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $admin->assignRole($role_r); //Assigning role to user
            }
        }
        return $this->success('用户添加成功');

    }

    /**
     * 显示指定后台管理用户.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin.managements');
    }

    /**
     * 显示编辑管理用户表单
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $roles = Role::get();
        $admin_roles = $admin->roles()->pluck('id')->toArray();
        return view('admin.managements.edit', compact('admin','roles','admin_roles'));
    }

    /**
     * 更新指定后台管理用户
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'username'=>'required|string|max:40',
            'name'=>'required|string|max:40',
            'phone'=>'required|int|unique:users',
            'email'=>'required|string|email|max:255|unique:users',
        ]);
        $admin = Admin::findOrFail($id);
        $input = $request->only(['name', 'email', 'username','phone']);
        $roles = $request['roles']; // 获取所有角色
        $admin = $admin->fill($input)->save();

        $roles = $request['roles']; // 获取输入的角色字段
        // 检查是否某个角色被选中
        if (isset($roles)) {
            $admin->roles()->sync($roles);  // 如果有角色选中与用户关联则更新用户角色
        } else {
            $admin->roles()->detach(); // 如果没有选择任何与用户关联的角色则将之前关联角色解除
        }
        return $this->success('用户编辑成功');
    }

    /**
     * 删除给定的用户
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 通过给定id获取并删除用户
        if($id == 1){
            return $this->failed('该用户不允许删除',200);
        }
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return $this->success('用户删除成功');
    }

    /**
     *  获取权限列表数据接口(带分页)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request){
        $input = $request->all();
        //需要获取的列
        //$field = ['id','username','name','phone','email','created_at'];
        $lists = [];
        $managements = new Admin();
        //登录账号
        if(isset($input['username']) && !empty($input['username'])){
            $managements = $managements->where('username','like','%'.$input['username'].'%');
        }
        //手机号
        if(isset($input['phone']) && !empty($input['phone'])){
            $managements = $managements->where('phone','like','%'.$input['phone'].'%');
        }
        //邮箱
        if(isset($input['email']) && !empty($input['email'])){
            $managements = $managements->where('email','like','%'.$input['email'].'%');
        }
        $managements = $managements->get();//获取指定条件的用户

        //封装用户对应角色
        if(!empty($managements)){
            foreach($managements as $key => $management){
                $management_roles = $management->roles();
                $lists[$key] = $management->toArray();
                $lists[$key]['created_at'] = date('Y-m-d',strtotime($lists[$key]['created_at']));
                $lists[$key]['roles'] = $management_roles->pluck('chinese_name')->implode(',');
                $lists[$key]['role_id'] = $management_roles->pluck('id')->implode(',');
            }
        }
        //筛选指定角色的用户
        if(isset($input['role'])){
            if(!empty($lists)){
                foreach($lists as $key => $list){
                    if(!in_array($input['role'],explode(',',$list['role_id']))){
                        unset($lists[$key]);
                    }
                }
            }
        }
        //分页处理
        $total = count($lists); //符合条件的总条数
        //$pages_total = ceil($total/$input['limit']);//总页数
        $start = $input['limit']*($input['page']-1)+1;//开始标记
        $end = $input['page']*$input['limit'];        //结束标记
        $arr = [];
        for($start;$start<=$end ;$start++){
            if(!isset($lists[$start-1])){
                break;
            }
            $arr[] = $lists[$start-1];
        }
        $data = [
            'code'  =>  200,
            'message'   => '获取数据成功',
            'count' =>  $total,
            'data'  =>  $arr
        ];
        return \response()->json($data);
    }

    //个人设置页面展示
    public function personalIndex(){
        $admin = Auth::user();//当前用户信息
        $admin_roles = $admin->roles()->pluck('chinese_name')->implode(',');
        return view('admin.personal.index',compact('admin','admin_roles'));
    }
    //个人设置保存操作
    public function personalSave(Request $request){
        $this->validate($request,[
            'name'=>'required|string|max:40',
            'sex'=>'required|string|max:40',
            'avatar'=>'required|string|max:255',
            'phone'=>'required|int|unique:users',
            'email'=>'required|string|email|max:255|unique:users',
            'remarks'=>'string|max:255',
        ]);
        $admin = Admin::findOrFail($request['id']);
        $input = $request->only(['name', 'email', 'sex','avatar','phone','remarks']);
        $admin = $admin->fill($input)->save();
        return $this->success('编辑成功');
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
