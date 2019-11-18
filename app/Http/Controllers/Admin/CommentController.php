<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseController
{

    public function __construct()
    {
        //$this->middleware();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::all()->toArray();
        $categories = $this->recursiveArr($categories,0,0,'');//整理类目列表
        return view('admin.comments.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'context'=>'required|string|max:255',
            'article_id'=>'required|integer',
            'cate_id'=>'required|integer'
        ]);

        $comment = new Comment();
        $input = $request->only('context','article_id','cate_id');
        $current_guards = $this->getCurrentGuard();
        $input['guard_name'] = $current_guards;
        $input['reviewer_id'] = Auth::guard($current_guards)->user()->id;
        $input['ip'] = $request->getClientIp();
        if(isset($request['pid']) && !empty($request['pid'])){
            $input['pid'] = $request['pid'];
        }else{
            $input['pid'] = 0;
        }
        $comment->fill($input)->save();
        return $this->success('编辑成功');
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
        $comment = Comment::findOrFail($id);
        $article_title = $comment->article()->pluck('title');
        $cate_name = $comment->category()->pluck('name');
        return view('admin.comments.edit',compact('comment','article_title','cate_name'));
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
            'context'=>'required|string|max:255'
        ]);
        $comment = Comment::findOrFail($id);
        $input = $request->only('context');
        $comment->fill($input)->save();
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
        $comment = Comment::findOrFail($id);
        $input = ['is_deleted'=>1];
        $comment->fill($input)->save(); //软删除
        return $this->success('删除成功');
    }

    //批量删除
    public function batchDestroy(Request $request){
        $ids = $request['ids'];
        $input = ['is_deleted'=>1];
        foreach($ids as $key => $id){
            $comment = Comment::findOrFail($id);
            $comment->fill($input)->save(); //软删除
        }
        //$category->destroy($ids);
        return $this->success('删除成功');
    }

    public function getData(Request $request){

        $input = $request->all();
        $field = ['id','article_id', 'cate_id', 'guard_name','reviewer_id','reviewer_name','ip','context','is_deleted','created_at','updated_at'];
        $comment = new Comment();
        //判断栏目
        if(isset($input['cate_id']) && !empty($input['cate_id'])){
            $comment = $comment->where('cate_id','=',$input['cate_id']);
        }
        //判断ip
        if(isset($input['ip']) && !empty($input['ip'])){
            $comment = $comment->where('ip','like','%'.$input['ip'].'%');
        }
        //只有超管可以查看所有评论,其他人只能查看自己的评论
        //判断当前guard,只判断当前登录的用户
        $current_guards = $this->getCurrentGuard();
        if(!Auth::guard($current_guards)->user()->hasAnyRole(['super-admin'])){
            $comment = $comment->where('guard_name','=',$current_guards);
            $comment = $comment->where('reviewer_id','=',Auth::guard($current_guards)->user()->id);
        }

        $comment = $comment->where('is_deleted','=',0);

        $comment_list = $comment->orderBy('id','desc')->paginate($input['limit'],$field,null,$input['page']);//获取所有权限;
        //存放评论数据
        $arr = [];
        if(!empty($comment_list)){
            foreach($comment_list as $key => $list){
                $article_title = $list->article()->pluck('title');
                $arr[$key] = $list->toArray();
                $arr[$key]['article_title'] = $article_title;
            }
        }
        $comment_list = $comment_list->toArray();
        $data = [
            'code'  =>  200,
            'message'   => '获取数据成功',
            'count' =>  $comment_list['total'],
            'data'  =>  $arr
        ];
        return \response()->json($data);
    }
}
