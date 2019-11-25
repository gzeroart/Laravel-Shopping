<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodsController extends Controller
{
    //上传
    public function upload2(Request $request)
    {
        if ($request->isMethod('POST')) { //判断是否是POST上传，应该不会有人用get吧，恩，不会的
            $Route_icon = '/uploads/' . date('Ymd') . '/';
            //在源生的php代码中是使用$_FILE来查看上传文件的属性
            //但是在laravel里面有更好的封装好的方法，就是下面这个
            //显示的属性更多
            $fileCharater = $request->file('source');

            if ($fileCharater->isValid()) { //括号里面的是必须加的哦
                //如果括号里面的不加上的话，下面的方法也无法调用的

                //获取文件的扩展名 
                $ext = $fileCharater->getClientOriginalExtension();

                //获取文件的绝对路径
                $path = $fileCharater->getRealPath();

                //定义文件名
                $filename = date('Y-m-d-h-i-s') . '.' . $ext;

                //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
                Storage::disk('uploads')->put($filename, file_get_contents($path));
                //输出文件路径
                echo  $Route_icon . $filename;
                // echo $url = uploads::url($filename);
            }
        }
        return view('111');
    }
    //图片上传
    public function upload(Request $request)
    {
        if ($request->isMethod('POST')) { //判断是否是POST上传，应该不会有人用get吧，恩，不会的
            $Route_icon = '/uploads/' . date('Ymd') . '/';
            //在源生的php代码中是使用$_FILE来查看上传文件的属性
            //但是在laravel里面有更好的封装好的方法，就是下面这个
            //显示的属性更多
            $fileCharater = $request->file('source');

            if ($fileCharater->isValid()) { //括号里面的是必须加的哦
                //如果括号里面的不加上的话，下面的方法也无法调用的

                //获取文件的扩展名 
                $ext = $fileCharater->getClientOriginalExtension();

                //获取文件的绝对路径
                $path = $fileCharater->getRealPath();

                //定义文件名
                $filename = date('Y-m-d-h-i-s') . '.' . $ext;

                //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
                Storage::disk('uploads')->put($filename, file_get_contents($path));
                //输出文件路径
                echo  $Route_icon . $filename;
                // echo $url = uploads::url($filename);
            }
        }
    }
    //商品名 商品分类 商品概要说明 店内价格 市场价格	数量	热门商品	状态	更新时间	更新者	操作

    //商品管理 
    public function productList()
    {

        //product 商品表 product_category商品分类表
        $users = DB::table('product')->get();
        // dump($user);
        $us = array();
        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "name" => $user->name, //名字
                "categoryid" => $this->getcategoryid($user->category_id), //类别ID
                "explain" => $user->explain, //商品说明
                "generalexplain" => $user->general_explain, //解释
                "discount" => $user->discount, //折扣
                "shopprice" => number_format(($user->shop_price / 100), 2), //商店价格
                "price" => number_format(($user->price / 100), 2), //价格
                "externalid" => $user->external_id, //
                "quantity" => $user->quantity, //量
                "hot" => $this->hotinfo($user->hot), //热
                "productstatus" => $this->productstatus($user->product_status), //产品状态
                "inventoryflag" => $user->inventory_flag, //库存标志
                "defaultimg" => $user->default_img, //商品图片
                "updatetime" => $user->update_time, //更新时间
                "updateuserid" => $user->update_user_id, //更新者ID
                "createtime" => $user->create_time, //创建时间
                "createuserid" => $user->create_user_id, //创建用户id
            ));
        }

        // dump($us);
        return view("productList", ['us' => $us, 'pageOn' => 'productList']);
    }

    public function hotinfo($_id)
    {
        if ($_id == 0) {
            return "非热门商品";
        } else {
            return "热门商品";
        }
    }

    public function productstatus($_id)
    {
        if ($_id == 0) {
            return "已上架";
        } else {
            return "已下架";
        }
    }

    //获取商品分类信息
    public function getcategoryid($_id)
    {
        $user = DB::table('product_category')->where('id', $_id)->value('name');
        return $user;
    }


    //OPtion管理
    public function option()
    {
        $user = DB::table('option_info')->get();
        $us = array();
        foreach ($user as $k => $user) {
            //合并数组
            array_push($us, array(
                "id" => $user->id, //ID
                "title" => $user->id, //option编号
                "date" => $user->name, //option名称
            ));
        }
        return view("optioninfo", ['us' => $us, 'pageOn' => 'option']);
    }

    //option添加页面
    public function addoption()
    {
        return view("addoption", ['pageOn' => 'option']);
    }
    //编辑页面
    public function optionedit(Request $request)
    {
        $userid = $request->id;
        $name = DB::table('option_info')->where('id', $userid)->value('name');
        $user = DB::table('option_value')->where('option_id', $userid)->get();
        $us = array();
        foreach ($user as $key => $value) {
            array_push($us, array(
                "id" => $value->id, //ID
                "name" => $value->name, //option名称
            ));
        }
        $option['name'] = $name;
        $option['id'] = $userid;
        $option['val'] = $us;
        return view('editOption', ['pageOn' => 'option', 'opt' => $option]);
    }
    //商品分类管理
    public function goodssort()
    {
        //SELECT c.id, c.name, c.sort_order AS sortOrder , c.image, c.update_time AS updateTime,c.description ,u.user_name AS updateUserName FROM product_category c LEFT OUTER JOIN USER u ON c.update_user_id = u.id WHERE 1=1 ORDER BY c.sort_order DESC 
        $_sql = "SELECT c.id, c.name, c.sort_order AS sortOrder , c.image, c.update_time AS updateTime,c.description ,u.user_name AS updateUserName FROM product_category c LEFT OUTER JOIN USER u ON c.update_user_id = u.id WHERE 1=1 ORDER BY c.sort_order DESC ";
        $users = DB::select($_sql);
        $us = array();
        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "id" => $user->id, //ID
                "category" => $user->name, //商品类名
                "description" => $user->description, //	描述
                "date" => $user->updateTime, //更新日期
                "user" => $user->updateUserName, //更新者
                "image" => $user->image, //图片,
                "sort" => $user->sortOrder, //排序
            ));
        }

        return view("productCategoryList", ['us' => $us, 'pageOn' => 'productCategoryList']);
    }

    //商品分类管理->修改
    public function productmod(Request $request)
    {
        //校准时间
        date_default_timezone_set("Asia/Shanghai");
        $userid = $request->id; //ID
        $username = $request->name; //商品类名
        $userdesc = $request->desc; //描述
        $usersort = $request->sort; //排序
        $usericon = $request->icon; //图片
        $usertime = date("Y-m-d H:i:s"); //时间
        $useradmin =  session()->get('isUsername'); //管理员
        $user_id = DB::table('user')->where('user_name', $useradmin)->value('id');
        $user = DB::update('update product_category set name=?,sort_order=?,description=?,image=?,update_time=?,update_user_id=? where id = ?', [$username, $usersort, $userdesc, $usericon, $usertime, $user_id, $userid]);
        if ($user >= 0) {
            $data['code'] = 200;
            $data['msg'] = '修改成功';
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '修改成功';
            echo json_encode($data);
        }
    }
    //商品分类管理->添加
    public function productadd(Request $request)
    {
        //校准时间
        date_default_timezone_set("Asia/Shanghai");
        $username = $request->name; //商品类名
        $usersort = $request->sort; //排序
        $usericon = $request->icon; //图片
        $userdesc = $request->desc; //描述
        $usertime = date("Y-m-d H:i:s"); //时间
        $useradmin =  session()->get('isUsername'); //管理员
        $userid = DB::table('user')->where('user_name', $useradmin)->value('id');
        // //product_category
        $user = DB::insert('insert into product_category (id, name, sort_order, description, image, update_time, update_user_id) values (?, ?, ?, ?, ?, ?, ?)', [NULL, $username, $usersort, $userdesc, $usericon, $usertime, $userid]);
        if ($user) {
            $data['code'] = 200;
            $data['msg'] = '分类添加成功';
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '分类添加失败';
            echo json_encode($data);
        }
    }

    //商品分类管理->删除
    public function productdel(Request $request)
    {
        $userid = $request->id;
        $idAll = implode(',', $userid); //数组转换字符串
        $_sql = "delete from product_category where id in (" . $idAll . ")";
        $user = DB::delete($_sql);
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

    //option删除
    public function optiondel(Request $request)
    {
        $userid = $request->id;
        $_sql = "delete from option_info where id = " . $userid;
        $user = DB::delete($_sql);
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

    //option添加
    public function optionadd(Request $request)
    {
        $username = $request->name;
        $userval = $request->val;
        //查询是否已经存在
        $opc = DB::table('option_info')->where('name', $username)->value('id');
        if ($opc == '') { //如果不存在 插入
            //插入option名称
            $user = DB::insert('insert into option_info (id, name,type) values (?, ?, ?)', [NULL, $username, 0]);
            if ($user) { //如果插入成功
                //获取插入后的ID
                $opid = DB::table('option_info')->where('name', $username)->value('id');
                if (is_array($userval)) {
                    foreach ($userval as $key => $v) {
                        if ($v['value'] != '') { //如果数据不为空 插入新参数
                            DB::insert('insert into option_value (id, option_id,name) values (?, ?, ?)', [NULL, $opid, $v['value']]);
                        }
                    }
                }
                $data['code'] = 200;
                $data['msg'] = '添加成功';
                echo json_encode($data);
            } else {
                $data['code'] = 204;
                $data['msg'] = '内容添加失败';
                echo json_encode($data);
            }
        } else {
            $data['code'] = 204;
            $data['msg'] = '内容已存在';
            echo json_encode($data);
        }
    }
    //option查询
    public function optionqus(Request $request)
    {
        $userid = $request->id;
        $username = $request->name;
        //如果编号为空
        if ($userid != '') {
            $userid = " AND id LIKE '%" . $userid . "%' ";
        }
        //如果名称为空
        if ($username != "") {
            $username = " AND name LIKE '%" . $username . "%' ";
        }
        $_sql = "SELECT * FROM option_info WHERE 1=1 " . $userid . $username;
        //使用SQL原生模式
        $users = DB::select($_sql);
        //判断查询内容
        if ($users == null) {
            $us = array();
            $data['code'] = 204;
            $data['msg'] = '查询到相关数据0条';
            $data['data'] = $us;
            echo json_encode($data);
        } else {
            $us = array();
            foreach ($users as $k => $user) {
                //合并数组
                array_push($us, array(
                    "id" => $user->id, //ID
                    "title" => $user->id, //option编号
                    "date" => $user->name, //option名称
                ));
            }
            $data['code'] = 200;
            $data['msg'] = '查询到相关数据' . count($us) . '条';
            $data['data'] = $us;
            echo json_encode($data);
        }
    }

    //option修改
    public function optionmod(Request $request)
    {
        $userid = $request->id;
        $username = $request->name;
        $userdata = $request->data;
        $user1 = DB::update('update option_info set name = ? where id = ?', [$username, $userid]);
        // dump($user1);
        if ($user1 >= 0) { //判断名称修改是否成功
            if (is_array($userdata)) { //判断参数数据是否是数组形式
                foreach ($userdata as $key => $value) {
                    if (isset($value['id'])) { //判断参数ID是否存在 不存在则添加
                        if ($value['value'] != '') {
                            $user2 = DB::update('update option_value set name = ? where id = ?', [$value['value'], $value['id']]);
                        }
                    } else {
                        $user3 = DB::insert('insert into option_value (id, option_id, name) values (?, ?, ?)', [NULL, $userid, $value['value']]);
                    }
                }
            }
            $data['code'] = 200;
            $data['msg'] = '保存成功';
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '保存失败';
            echo json_encode($data);
        }
    }
}
