<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ArticleController extends Controller
{
    //文章管理
    public function manage()
    {
        //AS自定一字段别名 a.category = ac.category_id文章表链接分类记录表id
        $_sql = "SELECT a.id AS id,a.content AS content,a.category AS category, a.update_time AS updateTime,a.update_user_id AS updateUserId,a.title AS title , ac.category_name AS articleCategoryName FROM article a LEFT JOIN article_category ac ON a.category = ac.category_id 
        ";
        $users = DB::select($_sql);
        //定义一个为空的数组
        $us = array();
        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "id" => $user->id, //ID
                "title" => $user->title, //标题
                "name" => $user->updateUserId, //更新者名
                "date" => $user->articleCategoryName, //分类名
                "province" => $user->updateTime //最后更新时间
            ));
        }
        return view('manage', ['us' => $us, 'pageOn' => 'articlemanage']);
    }


    //文章分类
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
        //获取登录者账号
        $isusername = session()->get('isUsername');
        return view('sort', ['us' => $us, 'pageOn' => 'articlesort']);
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
        //查询是否存在
        $user = DB::table('article_category')->where('category_name', $content)->first();
        //判断为空则不存在
        if ($user == null && $content != '') {
            //校准时间
            date_default_timezone_set("Asia/Shanghai");
            $user_time = date("Y-m-d H:i:s"); //记录更新时间
            $_sql = "update article_category set category_name = ?, update_time = ?  where category_id = ?";
            $user = DB::update($_sql, [$content, $user_time, $userid]);
            //如果返回大于0则修改成功
            if ($user >= 0) {
                $data['code'] = 200;
                $data['msg'] = '修改成功';
                $data['time'] = $user_time;
                echo json_encode($data);
            } else {
                $data['code'] = 204;
                $data['msg'] = '修改失败';
                echo json_encode($data);
            }
        } else {
            $data['code'] = 204;
            $data['msg'] = '修改分类已存在';
            echo json_encode($data);
        }
    }

    //添加分类
    public function sortaddrow(Request $request)
    {
        $content = $request->content;
        //验证数据是否为空
        if ($content != '') {
            //查询是否存在
            $user = DB::table('article_category')->where('category_name', $content)->first();
            //判断为空则不存在
            if ($user == null) {
                //校准时间
                date_default_timezone_set("Asia/Shanghai");
                $user_time = date("Y-m-d H:i:s"); //时间
                $isusername = session()->get('isUsername'); //操作者
                //设置数据库语句
                $_sql = "insert into `article_category` (category_id,category_name, update_user_id, update_time) values (?,?,?,?)";
                //执行数据库插入
                $user2 = DB::insert($_sql, [NULL, $content, $isusername, $user_time]);
                if ($user2) { //如果插入成功

                    //->orderBy排序 update_time条件 desc 降序
                    $users = DB::table('article_category')->orderBy('update_time', 'desc')->get();
                    //dump($users);
                    //定义一个为空的数组
                    $us = array();
                    foreach ($users as $k => $user) {
                        //合并数组
                        array_push($us, array(
                            "edit" => $user->category_id, //ID
                            "date" => $user->category_name, //分类名
                            "name" => $user->update_user_id, //添加者
                            "province" => $user->update_time //添加时间
                        ));
                    }
                    $data['code'] = 200;
                    $data['msg'] = '分类添加成功';
                    $data['data'] = $us;
                    echo json_encode($data);
                } else {
                    $data['code'] = 204;
                    $data['msg'] = '分类添加失败';
                    echo json_encode($data);
                }
            } else {
                $data['code'] = 204;
                $data['msg'] = '该分类已存在';
                echo json_encode($data);
            }
        } else {
            $data['code'] = 204;
            $data['msg'] = '数据不能为空';
            echo json_encode($data);
        }
    }

    //查询分类
    public function sortqusrow(Request $request)
    {
        $content = $request->name; //分类名
        $update_timeForm = $request->date1; //时间
        $update_timeTo = $request->date2; //时间2
        //接收到用户名
        if ($content != '') {
            $content = " and category_name like concat('%','" . $content . "','%') ";
        }
        //开始时间
        if ($update_timeForm != '') {
            $update_timeForm = " and date_format(update_time,'%Y-%m-%d')>= '" . $update_timeForm . "' ";
        }
        //结束时间
        if ($update_timeTo != '') {
            $update_timeTo = " and date_format(update_time,'%Y-%m-%d')<= '" . $update_timeTo . "' ";
        }
        //自定义查询语句
        $_sql = "select * from `article_category` where 1=1" . $content . $update_timeForm . $update_timeTo . " order by update_time desc;";
        //使用SQL原生模式
        $users = DB::select($_sql);
        if ($users == null) {
            //定义一个用于储存的数组'code' => 204, 'msg' => '无数据'
            $us = array();
            $data['code'] = 204;
            $data['msg'] = '查询到相关数据0条';
            $data['data'] = $us;
            echo json_encode($data);
        } else {
            //定义一个用于储存的数组 'code' => 200, 'msg' => '查询成功'
            $us = array();
            foreach ($users as $k => $user) {
                //合并数组
                array_push($us, array(
                    "edit" => $user->category_id, //ID
                    "date" => $user->category_name, //分类名
                    "name" => $user->update_user_id, //添加者
                    "province" => $user->update_time //时间
                ));
            }
            $data['code'] = 200;
            $data['msg'] = '查询到相关数据' . count($us) . '条';
            $data['data'] = $us;
            echo json_encode($data);
        }
    }
}
