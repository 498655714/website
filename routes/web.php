<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//网站前台用户认证
Auth::routes();

//前台用户管理
Route::group(['prefix'=>'user','namespace' => 'User'], function () {
    Route::resource('managements', 'UserController', ['names' => 'user.managements']);//后台管理用户路由
    Route::post('managements/getData','UserController@getData')->name('user.managements.getData');
});