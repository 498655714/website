<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //分类

    /**
     * @var string
     */
    //protected $guard = 'admin';

    protected $table='categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = [
        'name','pid','sort'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    //栏目对应文章
    public function articles(){
        return $this->hasMany('articles','cate_id','id');
    }

    //栏目对应评论
    public function comments(){
        return $this->hasMany('comments','cate_id','id');
    }
}
