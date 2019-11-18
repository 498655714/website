<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    //

    /**
     * @var string
     */
    //protected $guard = 'admin';

    protected $table='comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = [
        'article_id', 'cate_id', 'guard_name','reviewer_id','reviewer_name','ip','context'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    //评论对应的文章
    public function article(){
        return $this->belongsTo('articles','article_id','id');
    }
    //评论对应的文章栏目
    public function category(){
        return $this->belongsTo('categories','cate_id','id');
    }
}
