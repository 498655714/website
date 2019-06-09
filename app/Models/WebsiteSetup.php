<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetup extends Model
{
    protected $table = 'website_setups';

    //数据库主键
    public $primaryKey = 'id';

    protected $fillable = ['name','value','describe'];
}
