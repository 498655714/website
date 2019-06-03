<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\Controller;
use Illuminate\Http\Request;

use Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Session;
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isAdmin']);//isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    /**
     * 显示角色列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index')->with('roles',$roles);
    }

    /**
     * 显示创建角色表单
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('roles.create',['permissions'=>$permissions]);
    }

    /**
     * 保存新创建的角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:15|unique:roles',
            'chinese_name'=>'required|max:20',
            'guard_name'=>'required|max:20',
        ]);
        $name = $request['name'];
        $role = new Role();
        $role->name = $name;
        $role->chinese_name = $request['chinese_name'];
        $role->guard_name = $request['guard_name'];
        $permissions = $request['permissions'] ?? [];

        $role->save();
        foreach($permissions as $permission){
            $p = Permission::where('id','=',$permission)->firstOrFail();
            $role = Role::where('name','=',$name)->first();
            $role->givePermissionTo($p);
        }

        return $this->success('角色已添加');
        //return redirect()->route('roles.index')->with('flash_message','角色已添加');
    }

    /**
     * 显示指定的角色
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('roles');
    }

    /**
     * 显示编辑角色表单
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $role_permissions = $role->permissions->toArray();
        $role_permissions_ids = [];
        if(!empty($role_permissions)){
            $role_permissions_ids = array_column($role_permissions,'id');
        }
        return view('roles.edit',compact('role','permissions','role_permissions_ids'));
    }

    /**
     * 更新编辑角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|max:15|unique:roles,name,'.$id,
            'chinese_name'=>'required|max:20',
            'guard_name'=>'required|max:20',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'] ?? [];
        $role->fill($input)->save();
        $p_all = Permission::all();
        foreach($p_all as $p){
            $role->revokePermissionTo($p);
        }

        foreach($permissions as $permission){
            $p = Permission::where('id','=',$permission)->firstOrFail();
            $role->givePermissionTo($p);
        }

        return $this->success('角色更新成功');
//        return redirect()->route('roles.index')->with('flash_message','角色已更新');
    }

    /**
     * 删除指定角色
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $this->success('角色删除成功');
        //return redirect()->route('roles.index')->with('flash_message','角色已删除');
    }


    /**
     *  获取角色列表数据接口(带分页)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request){
        $input = $request->all();
        $field = ['id','name','chinese_name','guard_name','created_at'];
        $roles = new Role();
        //权限中文名称
        if(isset($input['chinese_name']) && !empty($input['chinese_name'])){
            $roles = $roles->where('chinese_name','like','%'.$input['chinese_name'].'%');
        }
        //权限名称
        if(isset($input['name']) && !empty($input['name'])){
            $roles = $roles->where('name','like','%'.$input['name'].'%');
        }
        //权限组名称
        if(isset($input['guard_name']) && !empty($input['guard_name'])){
            $roles = $roles->where('guard_name','like','%'.$input['guard_name'].'%');
        }
        $roles = $roles->paginate($input['limit'],$field,null,$input['page'])->toArray();//获取所有权限
        $data = [
            'code' => 200,
            'msg'   => '成功',
            'count' =>$roles['total'],
            'data'  => $roles['data']
        ];
        return response()->json($data);
    }
}
