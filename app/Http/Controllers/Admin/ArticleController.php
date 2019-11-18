<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function sort()
    {
        $users = DB::table('article_category')->get();
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
        return view('sort', ['us' => $us]);
    }

    public function sortdelrow(Requset $request)
    {
        echo 'ee';
        // $userid = $request->id;
        // dump($userid);
    }
}
