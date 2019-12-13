<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //消息
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->comment('消息内容');
            $table->string('type')->comment('消息类型(公告announce、提醒remind、私信message)');
            $table->integer('target_id')->comment('目标的id(如文章id)');
            $table->string('target_type')->comment('目标类型(比如文章article)');
            $table->string('action')->comment('动作类型(例如点赞like)');
            $table->integer('sender_id')->comment('发送者ID');
            $table->string('sender_type')->comment('发送者类型(前台用户user、后台管理员admin)');
            $table->integer('is_read')->comment('阅读状态');
            $table->integer('receiver_id')->comment('消息接受者(如文章作者)');
            $table->string('receiver_type')->comment('消息接受者类型(前台用户user、后台管理员admin)');
            $table->timestamps();
        });

        //订阅
        Schema::create('subscriptions',function (Blueprint $table){
            $table->integer('target_id')->comment('目标的ID(比如文章ID)');
            $table->string('target_type')->comment('目标的类型(比如文章article)');
            $table->string('action')->comment('动作类型(比如点赞like)');
            $table->integer('subscriber_id')->comment('订阅用户');
            $table->string('subscriber_type')->comment('订阅用户类型');
        });

        /**订阅配置
         * 评论comment、喜欢like、收藏collection、关注follow
         *    {
         *     "comment":true,
         *     "like":true,
         *     "collection":true,
         *     "follow":true
         *    }
         */
        Schema::create('subscription_config',function (Blueprint $table){
            $table->json('config')->default('{"comment":true,"like":true,"collection":true,"follow":true}')->comment('配置');
            $table->integer('subscriber_id')->comment('订阅用户');
            $table->string('subscriber_type')->comment('订阅用户类型');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_config');
    }
}
