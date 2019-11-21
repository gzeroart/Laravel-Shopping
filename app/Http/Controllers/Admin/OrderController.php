<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    //订单列表
    public function orderList(Request $request)
    {
        $_sql = "select distinct t1.order_num, t1.price, t1.payment_flag, t1.user_id, t1.contact_name, t1.contact_mobile, t1.contact_address, t1.message, t1.status, t1.type, t1.create_time from order_info t1 WHERE ( type = 0 ) order by t1.create_time desc 
    ";
        $users = DB::select($_sql);
        //定义一个为空的数组
        $us = array();
        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "order_num" => $user->order_num, //订单号
                "price" => ($user->price / 100), //总价
                "payment_flag" => $this->payflag($user->payment_flag), //付款标志
                "status" => $this->status($user->status), //订单状态
                "contact_name" => $user->contact_name, //联系人
                "contact_mobile" => $user->contact_mobile, //联系电话
                "create_time" => $user->create_time, //订单时间
            ));
        }

        // "SELECT `p`.`id` AS `p_id` , `p`.`name` AS `p_name` , `p`.`category_id` AS `p_category_id` , `pc`.`name` AS `pc_category_name`, `p`.`explain` AS `p_explain` , `p`.`general_explain` AS `p_general_explain` , `p`.`discount` AS `p_discount` , `p`.`shop_price` AS `p_shop_price` , `p`.`price` AS `p_price` , `p`.`external_id` AS `p_external_id` , `p`.`quantity` AS `p_quantity` , `p`.`hot` AS `p_hot` , `p`.`product_status` AS `p_product_status` , `p`.`inventory_flag` AS `p_inventory_flag` , `p`.`default_img` AS `p_default_img`, `p`.`update_time` AS `p_update_time` , `us`.`user_name` AS `us_updateUserName` FROM `product` AS `p` left join `product_category` AS `pc` ON `p`.`category_id` = `pc`.`id` left join `user` AS `us` ON `p`.`update_user_id` = `us`.`id` where 1=1 and `p`.hot = NULL order by `p`.`update_time` DESC 
        // "



        return view("order", ['us' => $us, 'pageOn' => 'order']);
    }

    //付款标志
    public function payflag($_id)
    {
        if ($_id == 0) {
            return '未付';
        } else {
            return '已付';
        }
    }

    //订单状态
    public function status($_id)
    {
        switch ($_id) {
            case '0':
                return '订单编辑中';
                break;
            case '1':
                return '已下单';
                break;
            case '2':
                return '配送中';
                break;
            case '3':
                return '配送完成';
                break;
            case '4':
                return '订单取消';
                break;
            default:
                # code...
                break;
        }
    }

    //订单查询
    public function orderinfo(Request $request)
    {
        $username = $request->username; //订单编号
        $role = $request->role; //订单状态
        $lastLoginTimeForm = $request->lastLoginTimeForm; //开始时间
        $lastLoginTimeTo = $request->lastLoginTimeTo; //结束时间
        $enabled = $request->enabled; //付款标志
        $price1 = $request->price1; //总价开始
        $price2 = $request->price2; //总价结束

        //判断付款标志
        if ($enabled == 'all') {
            $enabled = '';
        }
        //判断订单状态
        if ($role == 'all') {
            $role = '';
        }

        //判断总价开始
        if ($price1 != '') {
            $price1 = " AND price >= " . $price1;
        }

        //判断总价结束
        if ($price2 != '') {
            $price2 = " AND price <= " . $price2;
        }

        //判断订单号
        if ($username != '') {
            $username = " AND t1.order_num = '" . $username . "' ";
        }

        //判断订单状态
        if ($role != '') {
            $role = " AND t1.status = " . $role;
        }

        //判断开始时间
        if ($lastLoginTimeForm != '') {
            $lastLoginTimeForm = " AND t1.create_time >= '" . $lastLoginTimeForm . "' ";
        }

        //判断结束时间
        if ($lastLoginTimeTo != '') {
            $lastLoginTimeTo = " AND t1.create_time <= '" . $lastLoginTimeTo . "' ";
        }
        //判断付款标志
        if ($enabled != '') {
            $enabled = " AND t1.payment_flag = " . $enabled;
        }

        //DISTINCT去掉重复的行
        $_sql = "SELECT DISTINCT t1.order_num, t1.price, t1.payment_flag, t1.user_id, t1.contact_name, t1.contact_mobile, t1.contact_address, t1.message, t1.status, t1.type, t1.create_time FROM order_info t1 WHERE 1=1 " . $price1 . $price2 . $username . $role . $lastLoginTimeForm . $lastLoginTimeTo . $enabled . " ORDER BY t1.create_time DESC";
        //执行查询
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
                    "username" => $user->order_num, //订单号
                    "nickname" => ($user->price / 100), //总价
                    "email" => $this->payflag($user->payment_flag), //付款标志
                    "enabled" => $this->status($user->status), //订单状态
                    "role" => $user->contact_name, //联系人
                    "lastLoginTime" => $user->contact_mobile, //联系电话
                    "registerTime" => $user->create_time, //订单时间
                ));
            }
            $data['code'] = 200;
            $data['msg'] = '查询到相关数据' . count($us) . '条';
            $data['data'] = $us;
            echo json_encode($data);
        }
    }

    //查看指定订单消息
    public function orderrow()
    {
        $users = DB::table('user')->get();
        dump($users);
        //定义一个空数组
        $us = array();
        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "username" => $user->order_num, //订单号
                "nickname" => ($user->price / 100), //总价
                "email" => $this->payflag($user->payment_flag), //付款标志
                "enabled" => $this->status($user->status), //订单状态
                "role" => $user->contact_name, //联系人
                "lastLoginTime" => $user->contact_mobile, //联系电话
                "registerTime" => $user->create_time, //订单时间
            ));
        }
    }
}
