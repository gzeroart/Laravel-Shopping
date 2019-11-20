<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommentController extends Controller
{
    //评论管理
    public function comment()
    {
        //->orderBy排序 update_time条件 desc 降序
        $_sql = "SELECT T1.id, T1.user_id, T2.user_name, T1.product_id, T3.name AS product_name, T1.stars, T1.create_time, T1.audit, T1.content FROM comments T1 LEFT JOIN USER T2 ON T1.user_id= T2.id LEFT JOIN product t3 ON t1.product_id=t3.id WHERE 1=1 ORDER BY T1.create_time DESC 
        ";
        $users = DB::select($_sql);
        //dump($users);
        //定义一个为空的数组
        $us = array();
        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "id" => $user->id, //ID
                "name" => $user->user_name, //用户名
                "tradeName" => $user->product_name, //商品名
                "content" =>  str_replace(array("\r\n", "\r", "\n"), "", $user->content), //内容 特殊字符处理
                "StarRated" => $this->StarRated($user->stars), //评论星级
                "time" => $user->create_time, //创建时间
                "state" => $this->status($user->audit) //审核状态
            ));
        }
        //获取登录者账号
        $isusername = session()->get('isUsername');
        return view('comment', ['us' => $us, 'pageOn' => 'comment']);
    }

    //审核状态
    public function status($sta)
    {
        if ($sta == 1) {
            return '已审核';
        } else {
            return '未审核';
        }
    }

    //评论星级
    public function StarRated($num)
    {
        $var1 = '';
        for ($i = 0; $i < $num; $i++) {
            $var1 .= '*';
        }
        return $var1;
    }
}
