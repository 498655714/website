<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isAdmin']);
    }

    /**
     * 显示权限列表.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permissions.index');
    }

    /**
     * 显示创建权限表单.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        return view('permissions.create')->with('roles',$roles);
    }

    /**
     * 保存新创建的权限.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:40',
            'chinese_name'=>'required|max:40',
            'guard_name'=>'max:40',
            ]);
        $name = $request['name'];
        $chinese_name = $request['chinese_name'];
        $guard_name = $request['guard_name'];
        $permission = new Permission();
        $permission->name = $name;
        $permission->guard_name = $guard_name;
        $permission->chinese_name = $chinese_name;
        $roles = $request['roles'];
        $permission->save();

        if(!empty($request['roles'])){
            foreach($roles as $role){
                $r = Role::where('id','=',$role)->firstOrFail();
                $permission = Permission::where('name','=',$name)->first();
                $r->givePermissionTo($permission);
            }
        }
        return $this->success('权限添加成功');

        //return redirect()->route('permissions.index')->with('success','权限已添加');

    }

    /**
     * 显示指定权限.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('permissions');
    }

    /**
     * 显示编辑权限表单
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * 更新指定权限
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|max:40',
            'chinese_name'=>'required|max:40',
            'guard_name'=>'max:40',
        ]);

        $input = $request->all();
        $permission->fill($input)->save();
        return $this->success('权限更新成功');
        //return redirect()->route('permissions.index')->with('success','权限已更新');
    }

    /**
     * 删除给定的权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        if($permission->name == "Administer roles & permissions"){
            return $this->failed('该权限不允许删除',200);
            //return redirect()->route('permissions.index')->with('errors',['无法删除该权限']);
        }
        $permission->delete();
        return $this->success('权限删除成功');
        //return redirect()->route('permissions.index')->with('success','权限已删除');
    }

    /**
     *  获取权限列表数据接口(带分页)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request){
        $input = $request->all();
        $field = ['id','name','chinese_name','guard_name','created_at'];
        $permissions = new Permission();
        //权限中文名称
        if(isset($input['chinese_name']) && !empty($input['chinese_name'])){
            $permissions = $permissions->where('chinese_name','like','%'.$input['chinese_name'].'%');
        }
        //权限名称
        if(isset($input['name']) && !empty($input['name'])){
            $permissions = $permissions->where('name','like','%'.$input['name'].'%');
        }
        //权限组名称
        if(isset($input['guard_name']) && !empty($input['guard_name'])){
            $permissions = $permissions->where('guard_name','like','%'.$input['guard_name'].'%');
        }
        $permissions = $permissions->paginate($input['limit'],$field,null,$input['page'])->toArray();//获取所有权限
        $data = [
            'code'  =>  200,
            'message'   => '获取数据成功',
            'count' =>  $permissions['total'],
            'data'  =>  $permissions['data']
        ];
        return \response()->json($data);
    }
}
