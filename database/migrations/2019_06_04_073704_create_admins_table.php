<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('phone')->unique();
            $table->string('name');
            $table->string('avatar')->comment('头像');
            $table->enum('sex',['男','女']);
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->uuid('uuid');
            $table->string('remarks')->comment('备注');
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
        Schema::dropIfExists('admins');
    }
}
