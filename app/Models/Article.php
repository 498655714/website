<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //

    /**
     * @var string
     */
    protected $guard = 'admin';

    protected $table='articles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = [
        'title', 'short_title', 'thumb','flag','cate_id','admin_id','writer'
        ,'keywords','description','content','click','is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}
