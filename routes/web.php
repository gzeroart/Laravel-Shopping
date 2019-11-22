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
Route::post('/admin/sort/del', 'Admin\ArticleController@sortdelrow');
//文章管理->分类管理->修改分类
Route::post('/admin/sort/mod', 'Admin\ArticleController@sortmodrow');
//文章管理->分类管理->添加分类
Route::post('/admin/sort/add', 'Admin\ArticleController@sortaddrow');
//文章管理->分类管理->查询分类
Route::post('/admin/sort/qus', 'Admin\ArticleController@sortqusrow');
//文章管理->文章管理->查询文章
Route::post('/admin/manage/qus', 'Admin\ArticleController@managequs');
//文章管理->文章管理->删除文章
Route::post('/admin/manage/del', 'Admin\ArticleController@managedel');

//文章管理->分类管理
Route::get('/admin/articlesort', 'Admin\ArticleController@sort');
//文章管理->文章管理
Route::get('/admin/articlemanage', 'Admin\ArticleController@manage');
##############################评论管理#####################################

//评论管理->评论查询
Route::post('/admin/comment/qus', 'Admin\CommentController@query');
//评论管理->评论审核
Route::post('/admin/comment/mod', 'Admin\CommentController@adoptrow');
//评论管理->评论批量审核
Route::post('/admin/comment/mods', 'Admin\CommentController@adoptall');
//评论管理->评论批量审核
Route::post('/admin/comment/del', 'Admin\CommentController@delAll');

//评论管理->评论管理
Route::get('/admin/comment', 'Admin\CommentController@comment');

##############################订单管理#####################################

//订单管理->订单查询
Route::post('/admin/order/qus', 'Admin\OrderController@orderinfo');

//订单管理->订单管理
Route::get('/admin/order', 'Admin\OrderController@orderList');
Route::get('/admin/111', 'Admin\OrderController@orderrow');

##############################商品管理#####################################

//商品管理->option
Route::get('/admin/option', function () {
    $us = array();
    return view("optioninfo", ['us' => $us, 'pageOn' => 'option']);
});



//productList
//商品管理->商品管理
Route::get('/admin/productList', function () {
    $us = array();
    return view("productList", ['us' => $us, 'pageOn' => 'productList']);
});

//商品管理->商品管理->商品分类管理
Route::get('/admin/productCategoryList', function () {
    $us = array();
    return view("productCategoryList", ['us' => $us, 'pageOn' => 'productCategoryList']);
});


##############################商品管理#####################################

Route::get('/admin', function () {
    //跳转redirect
    $isLogin = session()->get('isLogin'); //登录标识
    if ($isLogin) {
        echo '这是后台首页';
    } else {
        return redirect('../admin/login');
    }
});

Route::get('/', function () {
    echo "这是首页";
});

##############################电子钱包#####################################

//评论管理->评论批量审核
Route::post('/admin/pay/add', 'Admin\PayController@payadd');

//电子钱包->电子钱包
Route::get('/admin/pay', 'Admin\PayController@pay');


##############################路由结束#####################################