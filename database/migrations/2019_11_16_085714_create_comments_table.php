<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->comment('评论id');
            $table->integer('article_id',false,true)->comment('评论所属文章id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->integer('cate_id',false,true)->comment('文章栏目id');
            $table->foreign('cate_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('guard_name')->comment('守卫标识');
            $table->integer('reviewer_id',false,true)->comment('评论人id');
            $table->string('reviewer_name',150)->comment('评论人昵称');
            $table->ipAddress('ip')->comment('IP地址');
            $table->string('context',255)->comment('评论内容');
            $table->integer('pid')->default(0)->comment('评论上级id');
            $table->enum('is_deleted',[0,1])->default(0)->comment('是否已删除，1是 0 否');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
