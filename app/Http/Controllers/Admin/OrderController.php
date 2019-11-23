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
    public function orderrow(Request $request)
    {
        //order_info order_item product
        //
        //select t1.id, t1.order_num, t1.product_id, t1.price, t1.quantity, t1.user_id,t1.option_value_key_group, t1.json_data, t1.sku_id , t3.id as ProductId, t3.name as ProductName, t3.Category_id as ProductCategory_id, t3.General_explain as ProductGeneral_explain, t3.Discount as ProductDiscount, t3.Shop_price as ProductShop_price, t3.Price as ProductPrice, t3.Quantity as ProductQuantity, t3.Hot as ProductHot, t3.Product_status as ProductProduct_status, t3.default_img from order_item t1 left join product t3 on t1.product_id = t3.id LEFT JOIN order_info t4 ON t1.order_num = t4.order_num WHERE ( t1.user_id = 123522 and t1.order_num = 'O2016111900012' and t4.type = 0 )
        // $_sql = "select t1.id, t1.order_num, t1.product_id, t1.price, t1.quantity, t1.user_id,t1.option_value_key_group, t1.json_data, t1.sku_id , t3.id as ProductId, t3.name as ProductName, t3.Category_id as ProductCategory_id, t3.General_explain as ProductGeneral_explain, t3.Discount as ProductDiscount, t3.Shop_price as ProductShop_price, t3.Price as ProductPrice, t3.Quantity as ProductQuantity, t3.Hot as ProductHot, t3.Product_status as ProductProduct_status, t3.default_img from order_item t1 left join product t3 on t1.product_id = t3.id LEFT JOIN order_info t4 ON t1.order_num = t4.order_num WHERE ( t1.user_id = 123522 and t1.order_num = 'O2016110800005' and t4.type = 0 )";
        /*
 select order_num, price, payment_flag, user_id, contact_name, contact_mobile, contact_address, message, status, type, create_time from order_info where order_num = 'O2016110800005' 

select t1.id, t1.order_num, t1.product_id, t1.price, t1.quantity, t1.user_id,t1.option_value_key_group, t1.json_data, t1.sku_id , t3.id as ProductId, t3.name as ProductName, t3.Category_id as ProductCategory_id, t3.General_explain as ProductGeneral_explain, t3.Discount as ProductDiscount, t3.Shop_price as ProductShop_price, t3.Price as ProductPrice, t3.Quantity as ProductQuantity, t3.Hot as ProductHot, t3.Product_status as ProductProduct_status, t3.default_img from order_item t1 left join product t3 on t1.product_id = t3.id LEFT JOIN order_info t4 ON t1.order_num = t4.order_num WHERE ( t1.user_id = 123486 and t1.order_num = 'O2016110800005' and t4.type = 0 ) 

select

select id, company_name, logistics_num, order_num, state, contact_name, contact_mobile, contact_address, create_time,date_format(create_time,'%Y-%m-%d %H:%m:%d') AS timestamp from logistics WHERE ( order_num = 'O2016110800005' ) 

select id, order_num, status, note, update_user_id, create_time from order_history WHERE ( order_num = 'O2016110800005' ) order by create_time desc 
*/
        $userid = $request->id;
        //基本信息
        $users = DB::table('order_info')->where('order_num', $userid)->first();
        $us = array(
            "username" => $users->order_num, //订单号
            "enabled" => $this->status($users->status), //订单状态
            "role" => $users->contact_name, //联系人
            'address' => $users->contact_address, //地址
            "lastLoginTime" => $users->contact_mobile, //联系电话
            "registerTime" => $users->create_time, //订单时间
        );
        $data['info'] = $us; //储存基本信息数据

        //订单历史
        $his = DB::table('order_history')->where('order_num', $userid)->get();
        $dat_his = array();
        foreach ($his as $k => $_his) {
            //合并数组
            array_push($dat_his, array(
                "name" => $_his->note, //状态
                "date" => $_his->create_time, //时间
            ));
        }
        $data['his'] = $dat_his; //储存订单历史数据

        //获取参数
        $user_id = DB::table('order_item')->where('order_num', $userid)->get('product_id');
        foreach ($user_id as $key => $value) {
            $idAll[$key] = $value->product_id;
        }
        // 订单明细
        $dets = DB::table('product')->whereIn('id',  $idAll)->get();
        // dump($det);
        //定义一个数组
        $de = array();
        foreach ($dets as $k => $det) {
            //合并数组
            array_push($de, array(
                "name" => $det->name, //商品名称
                "num" => $det->category_id, //数量
                "amount" => ($det->shop_price / 100), //单价
            ));
        }
        $data['det'] = $de; //储存订单明细数据
        return view('orderedit', ['pageOn' => 'order', 'dat' => $data]);
    }
}
