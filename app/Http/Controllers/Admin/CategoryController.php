<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller as BaseController;

class CategoryController extends BaseController
{

    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //分类列表页
        $parent_cates = Category::where('pid',0)->get()->toArray();//获取一级分类列表
        return view('admin/categories/index')->with(['parent_cates'=>$parent_cates]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建分类
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string|max:40',
            'pid'=>'int',
            'sort'=>'int',
        ]);
        $input = $request->only(['name', 'pid', 'sort']);
        $category = new Category();
        $category->fill($input)->save();

        return $this->success('创建成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //编辑
        $categories = Category::findOrFail($id);
        return view('admin.categories.edit',compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request,[
            'name'=>'required|string|max:40',
            'pid'=>'int',
            'sort'=>'int',
        ]);
        $input = $request->only(['name', 'pid', 'sort']);
        $category = Category::findOrFail($id);
        $category->fill($input)->save();

        return $this->success('编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //查询该类的子类
        $ids = $this->recursiveDel($id);
        $ids[] = intval($id);
        Category::destroy($ids);
        return $this->success('删除成功');
    }

    /**
     *  获取分类列表数据接口
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request){
        $categories = new Category();
        $categories = $categories->get()->toArray();
        $data = [
            'code'  =>  200,
            'message'   => '获取数据成功',
            'data'  =>  $categories
        ];
        return \response()->json($data);
    }

    //递归获取id
    public function recursiveDel($pid){
        static $list = [];
        $category = Category::where(['pid'=>$pid])->get()->toArray();
        if(!empty($category)){
            foreach($category as $key=>$value){
                $list[] = $value['id'];
                $this->recursiveDel($value['id']);
            }
        }
        return $list;
    }
}
