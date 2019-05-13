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

Auth::routes();
Route::group(['namespace' => 'Admin'], function () {
    Route::get('/home', 'HomeController@index')->name('home');//后台主页
    Route::get('/home/console', 'HomeController@console')->name('console');//后台主页
});
