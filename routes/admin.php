<?php
/**
 * 注意：当在资源路由中使用其他方法，必须在资源路由之前进行注册路由
 */
//后台管理用户
Route::group(['prefix'=>'admin','namespace' => 'Admin'], function () {


    //后台管理用户登录认证
    Route::get('login','Auth\LoginController@showAdminLoginForm')->name('admin.login');
    Route::post('login','Auth\LoginController@adminLogin')->name('admin.login');
    Route::post('logout','Auth\LoginController@logout')->name('admin.logout');

    Route::get('register','Auth\RegisterController@showAdminRegisterForm')->name('admin.register');
    Route::post('register','Auth\RegisterController@createAdmin')->name('admin.register');

    Route::get('password/request','Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset','Auth\ResetPasswordController@reset')->name('admin.password.reset');

    //后台主页
    Route::get('home', 'HomeController@index')->name('admin.home');
    //后台控制台
    Route::get('home/console', 'HomeController@console')->name('admin.console');

    //个人资料设置、更新密码
    Route::get('personal','AdminController@personalIndex')->name('admin.personal.index');
    Route::post('personal','AdminController@personalSave')->name('admin.personal.index');
    Route::get('setpass','AdminController@setPassword')->name('admin.personal.setpass');
    Route::post('setpass','AdminController@setPasswordUpdate')->name('admin.personal.setpass');

    //分类管理
    Route::match(['get', 'post'],'categories/getData','CategoryController@getData')->name('admin.categories.getData');
    Route::resource('categories','CategoryController',['names'=>'admin.categories']);

    //文章管理
    Route::match(['get', 'post'],'articles/getData','ArticleController@getData')->name('admin.articles.getData');
    Route::post('articles/batchDestroy','ArticleController@batchDestroy')->name('admin.articles.batchDestroy');
    Route::resource('articles','ArticleController',['names'=>'admin.articles']);

    //评论管理
    Route::match(['get','post'],'comments/getData','CommentController@getData')->name('admin.comments.getData');
    Route::post('comments/batchDestroy','CommentController@batchDestroy')->name('admin.comments.batchDestroy');
    Route::resource('comments','CommentController',['names'=>'admin.comments']);

    //超级管理员才拥有访问权限
    Route::group(['middleware' => ['role:super-admin']], function () {
        //权限
        Route::post('permissions/getData','PermissionController@getData')->name('admin.permissions.getData');
        Route::resource('permissions','PermissionController',['names'=>'admin.permissions']);

        //角色
        Route::post('roles/getData','RoleController@getData')->name('admin.roles.getData');
        Route::resource('roles','RoleController',['names'=>'admin.roles']);

        //站点设置
        Route::get('websiteSetup/index','WebsiteSetupController@index')->name('admin.websiteSetup.index');
        Route::post('websiteSetup/store','WebsiteSetupController@store')->name('admin.websiteSetup.store');

        //后台用户管理
        Route::post('managements/getData','AdminController@getData')->name('admin.managements.getData');
        Route::resource('managements','AdminController',['names'=>'admin.managements']);//后台管理用户路由

    });

});
