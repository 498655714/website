<?php


//后台管理用户
Route::group(['prefix'=>'admin','namespace' => 'Admin'], function () {

    //后台管理用户认证
    Route::get('login','Auth\LoginController@showAdminLoginForm')->name('admin.login');
    Route::post('login','Auth\LoginController@adminLogin')->name('admin.login');
    Route::post('logout','Auth\LoginController@logout')->name('admin.logout');

    Route::get('register','Auth\RegisterController@showAdminRegisterForm')->name('admin.register');
    Route::post('register','Auth\RegisterController@createAdmin')->name('admin.register');

    Route::get('password/request','Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset','Auth\ResetPasswordController@reset')->name('admin.password.reset');

    Route::get('home', 'HomeController@index')->name('admin.home');//后台主页
    Route::get('home/console', 'HomeController@console')->name('admin.console');//后台控制台

    //权限
    Route::resource('permissions','PermissionController',['names'=>'admin.permissions']);
    Route::post('permissions/getData','PermissionController@getData')->name('admin.permissions.getData');

    //角色
    Route::resource('roles','RoleController',['names'=>'admin.roles']);
    Route::post('roles/getData','RoleController@getData')->name('admin.roles.getData');

    //站点设置
    Route::get('websiteSetup/index','WebsiteSetupController@index')->name('admin.websiteSetup.index');
    Route::post('websiteSetup/store','WebsiteSetupController@store')->name('admin.websiteSetup.store');
});
