<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',150)->comment('标题');
            $table->string('short_title',50)->comment('简短标题');
            $table->string('thumb',200)->nullable()->comment('缩略图');
            $table->string('flag',50)->nullable()->comment('推荐位 头条[h] 推荐[c] 幻灯[f] 特荐[a] 滚动[s] 加粗[b] 图片[p] 跳转[j] ');
            $table->integer("cate_id",false,true)->comment('分类id');
            $table->foreign('cate_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('guard_name')->comment('守卫标识');
            $table->integer('writer_id',false,true)->comment('编辑人id');
            $table->string('writer',30)->comment('作者');
            $table->string('keywords',100)->comment('关键字');
            $table->string('description',255)->comment('内容摘要');
            $table->mediumText('content')->nullable()->comment('文章正文');
            $table->unsignedMediumInteger('click')->default(0)->comment('点击率');
            $table->enum('is_deleted',[0,1])->default(0)->comment('是否删除，1是、0否');
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
        Schema::dropIfExists('articles');
    }
}
