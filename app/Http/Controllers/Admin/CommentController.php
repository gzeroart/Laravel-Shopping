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

    //评论查询
    public function query(Request $request)
    {
        $content = $request->name; //商品名
        $update_timeForm = $request->date1; //开始时间
        $update_timeTo = $request->date2; //时间2
        //接收到商品名
        if ($content != '') {
            $content = " and T3.name like concat('%','" . $content . "','%') ";
        }
        //开始时间
        if ($update_timeForm != '') {
            $update_timeForm = " and date_format(T1.create_time,'%Y-%m-%d')>= '" . $update_timeForm . "' ";
        }
        //结束时间
        if ($update_timeTo != '') {
            $update_timeTo = " and date_format(T1.create_time,'%Y-%m-%d')<= '" . $update_timeTo . "' ";
        }
        //自定义查询语句
        $_sql = "select T1.id, T1.user_id, T2.user_name, T1.product_id, T3.name AS product_name, T1.stars, T1.create_time, T1.audit, T1.content from comments T1 left join user T2 ON T1.user_id= T2.id left join product t3 ON t1.product_id=t3.id where 1=1" . $content . $update_timeForm . $update_timeTo . " order by T1.create_time desc;";
        //使用SQL原生模式
        $users = DB::select($_sql);

        //判断数据是否存在
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
                    "id" => $user->id, //ID
                    "name" => $user->user_name, //用户名
                    "tradeName" => $user->product_name, //商品名
                    "content" =>  str_replace(array("\r\n", "\r", "\n"), "", $user->content), //内容 特殊字符处理
                    "StarRated" => $this->StarRated($user->stars), //评论星级
                    "time" => $user->create_time, //创建时间
                    "state" => $this->status($user->audit) //审核状态
                ));
            }
            $data['code'] = 200;
            $data['msg'] = '查询到相关数据' . count($us) . '条';
            $data['data'] = $us;
            echo json_encode($data);
        }
    }

    //通过审核
    public function adoptrow(Request $request)
    {
        $userid = $request->id;
        $audit = $request->audit;

        //如果audit为1通过 否则为不通过
        if ($audit == 1) {
            $user = DB::update('update comments set audit = 1 where id = ?', [$userid]);
            $ps = '审核已通过';
            $res = '已审核';
        } else {
            $user = DB::update('update comments set audit = 0 where id = ?', [$userid]);
            $ps = '审核已驳回';
            $res = '未审核';
        }
        if ($user >= 0) {
            $data['code'] = 200;
            $data['msg'] = $ps;
            $data['res'] = $res;
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '操作失败';
            echo json_encode($data);
        }
    }

    //批量审核
    public function adoptall(Request $request)
    {
        $userdata = $request->data;
        $audit = $request->audit;
        foreach ($userdata as $key => $dat) {
            $usreidAll[$key] = $dat['id'];
        }
        $res = '';
        $idAll = implode(',', $usreidAll);
        if ($audit == 1) { //通过
            $_sql = "UPDATE comments SET audit = 1 WHERE id IN (" . $idAll . ")";
            $user = DB::update($_sql);
            $ps = '审核已全部通过';
            $res = '已审核';
        } else if ($audit == 0) { //驳回
            $_sql = "UPDATE comments SET audit = 0 WHERE id IN (" . $idAll . ")";
            $user = DB::update($_sql);
            $ps = '审核已全部驳回';
            $res = '未审核';
        } else if ($audit == 2) { }

        if ($user >= 0) {
            $data['code'] = 200;
            $data['msg'] = $ps;
            $data['res'] = $res;
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '操作失败';
            $data['res'] = $res;
            echo json_encode($data);
        }
    }

    //删除评论
    public function delAll(Request $request)
    {
        $userdata = $request->data;
        $audit = $request->audit;
        //判断数值是否为数组
        if (isset($userdata[0])) {
            foreach ($userdata as $key => $dat) {
                $usreidAll[$key] = $dat['id'];
            }
            $idAll = implode(',', $usreidAll); //数组转换字符串
        } else {
            $idAll = $userdata['id'];
        }
        $res = '';
        $_sql = "DELETE FROM comments WHERE id IN (" . $idAll . ")";
        //执行删除操作
        $user = DB::delete($_sql);

        //如果返回值不大与等于0 则删除失败
        if ($user >= 0) {
            $_sql = "SELECT T1.id, T1.user_id, T2.user_name, T1.product_id, T3.name AS product_name, T1.stars, T1.create_time, T1.audit, T1.content FROM comments T1 LEFT JOIN USER T2 ON T1.user_id= T2.id LEFT JOIN product t3 ON t1.product_id=t3.id WHERE 1=1 ORDER BY T1.create_time DESC";
            $users = DB::select($_sql);
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
            $data['code'] = 200;
            $data['msg'] = '删除成功';
            $data['data'] = $us;
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '删除失败';
            echo json_encode($data);
        }
    }

    //批量删除方法 DELETE FROM `shopping`.`article_category` WHERE `category_id` IN (8,9)
    //批量添加INSERT INTO MyTable(ID,NAME) VALUES(7,'003'),(8,'004'),(9,'005')
    //批量修改UPDATE comments SET audit = 2 WHERE id IN (47,46)
}
