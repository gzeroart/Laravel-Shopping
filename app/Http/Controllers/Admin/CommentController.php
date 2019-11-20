<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommentController extends Controller
{
    public function comment()
    {
        //->orderBy排序 update_time条件 desc 降序
        $users = DB::table('article_category')->orderBy('update_time', 'desc')->get();
        //dump($users);
        //定义一个为空的数组
        $us = array();

        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "id" => $user->category_id, //ID
                "title" => $user->category_name, //分类名
                "name" => $user->update_user_id, //添加者
                "time" => $user->update_time //添加时间
            ));
        }
        //获取登录者账号
        $isusername = session()->get('isUsername');
        return view('comment', ['us' => $us]);
    }
}
