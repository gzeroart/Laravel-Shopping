<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ArticleController extends Controller
{
    public function sort()
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
        return view('sort', ['us' => $us]);
    }

    //删除分类
    public function sortdelrow(Request $request)
    {
        //获取指定ID参数
        $userid = $request->id;
        $_sql = "delete from article_category where category_id=" . $userid;
        //执行删除操作
        $user = DB::delete($_sql);
        //如果删除操作返回结果大于等于0则删除成功
        if ($user >= 0) {
            $data['code'] = 200;
            $data['msg'] = '删除成功';
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '删除失败';
            echo json_encode($data);
        }
    }

    //修改分类
    public function  sortmodrow(Request $request)
    {
        $userid = $request->id;
        $content = $request->content;
        $_sql = "update article_category set category_name = ? where category_id = ?";
        $user = DB::update($_sql, [$userid, $content]);
        dump($user);
    }
}
