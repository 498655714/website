<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //

    /**
     * @var string
     */
    //protected $guard = 'admin';

    protected $table='articles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = [
        'title', 'short_title', 'thumb','flag','cate_id','guard_name','writer_id','writer'
        ,'keywords','description','content','click','is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
    //文章对应评论
    public function comments(){
        return $this->hasMany('comments','article_id','id');
    }
    //文章对应栏目
    public function category(){
        return $this->belongsTo('categories','cate_id','id');
    }
}
