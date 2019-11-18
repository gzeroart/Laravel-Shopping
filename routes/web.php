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

##############################后台登录#####################################
//登录验证
Route::post('/admin/logchect', "Admin\LoginController@userlogin");

//登录页面
Route::get('/admin/login', "Admin\LoginController@login");

##############################用户管理#####################################
//用户数据查询
Route::post('/admin/quserInfo', "Admin\UserController@quserInfo");
//用户单个查询
Route::post('/admin/oneUser', "Admin\UserController@oneUser");
//用户数据修改保存
Route::post('/admin/user/editUser', "Admin\UserController@editUser");
//添加新的用户
Route::post('/admin/user/addUserInfo', "Admin\UserController@addUserInfo");

//用户管理页面
Route::get('/admin/user', "Admin\UserController@user");
//用户管理->添加管理员页面
Route::get('/admin/user/add', "Admin\UserController@addUser");
//用户管理->编辑页面
Route::get('/admin/user/{id}', "Admin\UserController@oneUser");

##############################文章管理#####################################

//文章管理->分类管理->删除分类
Route::post('/admin/article/sort/del', 'Admin\ArticleController@sortdelrow');
//文章管理->分类管理->修改分类
Route::post('/admin/article/sort/mod', 'Admin\ArticleController@sortmodrow');
//文章管理->分类管理->添加分类
Route::post('/admin/article/sort/add', 'Admin\ArticleController@sortaddrow');
//文章管理->分类管理->查询分类
Route::post('/admin/article/sort/qus', 'Admin\ArticleController@sortqusrow');

//文章管理->分类管理
Route::get('/admin/article/sort', 'Admin\ArticleController@sort');

##############################评论管理#####################################

Route::get('/admin', function () {
    echo "string";
});
