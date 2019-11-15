<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class ArticleController extends BaseController
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
        $flags = config('app.flags');
        $categories = Category::get()->toArray();
        $categories = $this->recursiveArr($categories,0);//整理类目列表
        return view('admin.articles.index',compact('flags','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $flags = config('app.flags');
        $categories = Category::get()->toArray();
        $categories = $this->recursiveArr($categories,0);//整理类目列表
        return view('admin.articles.create',compact('flags','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'title'=>'required|string|max:150',
            'short_title'=>'required|string|max:50',
            'thumb'=>'max:200',
            'cate_id'=>'required|integer',
            'writer'=>'required|string|max:30',
            'keywords'=>'required|string|max:100',
            'description'=>'required|string|max:255',
        ]);
        $input = $request->only(['title', 'short_title', 'thumb','writer','cate_id','keywords','description','content']);
        $input['flag'] = implode(',',$request['flag']);
        $input['admin_id']  = Auth::user()->id;
        $category = new Article();
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
        //
        $flags = config('app.flags');
        $categories = Category::get()->toArray();
        $categories = $this->recursiveArr($categories,0);//整理类目列表
        $article = Article::findOrFail($id);
        return view('admin.articles.edit',compact('flags','categories','article'));
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
        $this->validate($request,[
            'title'=>'required|string|max:150',
            'short_title'=>'required|string|max:50',
            'thumb'=>'max:200',
            'cate_id'=>'required|integer',
            'writer'=>'required|string|max:30',
            'keywords'=>'required|string|max:100',
            'description'=>'required|string|max:255',
        ]);
        $input = $request->only(['title', 'short_title', 'thumb','writer','cate_id','keywords','description','content']);
        $input['flag'] = implode(',',$request['flag']);
        $category = Article::findOrFail($id);
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
        //
        $category = Article::findOrFail($id);
        $category->delete();
        return $this->success('删除成功');
    }

    /**
     *  批量删除
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy(Request $request){
        $ids = $request['ids'];
        $category = new Article();
        $category->destroy($ids);
        return $this->success('删除成功');
    }
    /**
     *  获取文章列表数据接口(分页)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request){
        $input = $request->all();
        $field = ['id','title', 'short_title', 'thumb','flag','cate_id','admin_id','writer'
            ,'keywords','description','content','click','is_deleted','created_at','updated_at'];
        $articles = new Article();
        $categories = Category::all()->toArray();
        $categories = empty($categories) ? [] : array_column($categories,'name','id');
        $flags = config('app.flags');

        //标题
        if(isset($input['title']) && !empty($input['title'])){
            $articles = $articles->where('title','like','%'.$input['title'].'%');
        }
        //作者
        if(isset($input['writer']) && !empty($input['writer'])){
            $articles = $articles->where('writer','like','%'.$input['writer'].'%');
        }
        //栏目
        if(isset($input['cate_id']) && !empty($input['cate_id'])){
            $articles = $articles->where('cate_id','=',$input['cate_id']);
        }
        //文章id
        if(isset($input['id']) && !empty($input['id'])){
            $articles = $articles->where('id','=',$input['id']);
        }
        //创建时间
        if(isset($input['created_at']) && !empty($input['created_at'])){
            $created = explode(' - ',$input['created_at']);
            $articles = $articles->where([
                ['created_at','>',$created[0]],
                ['created_at','<',$created[1]]
            ]);
        }
        //只有超管可以查看所有文章
        if(!Auth::user()->hasAnyRole(['super-admin'])){
            $articles = $articles->where('admin_id','=',Auth::user()->id);
        }
        $articles_list = $articles->paginate($input['limit'],$field,null,$input['page'])->toArray();//获取所有权限;
        //整理数据
        foreach($articles_list['data'] as $key => $article){
            $tmp = '';
            if(!empty($article['flag'])){
                foreach(explode(',',$article['flag']) as $flag_key => $flag_value){
                    $tmp .= $flags[$flag_value].'['.$flag_value.'] ';
                }
            }
            $articles_list['data'][$key]['flag_name'] = $tmp;
            empty($categories) ? $articles_list['data'][$key]['category'] = '' : $articles_list['data'][$key]['category'] = $categories[$article['cate_id']];
        }
        $data = [
            'code'  =>  200,
            'message'   => '获取数据成功',
            'count' =>  $articles_list['total'],
            'data'  =>  $articles_list['data']
        ];
        return \response()->json($data);
    }

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

}
