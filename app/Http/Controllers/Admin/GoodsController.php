<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodsController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->isMethod('POST')) { //判断是否是POST上传，应该不会有人用get吧，恩，不会的

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
                Storage::disk('public')->put($filename, file_get_contents($path));
                echo  $filename;
            }
        }
        return view('111');
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
    public function optionedit()
    {
        return view('editOption', ['pageOn' => 'option']);
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
                "description" => $user->sortOrder, //	描述
                "date" => $user->updateTime, //更新日期
                "user" => $user->updateUserName, //更新者
            ));
        }

        return view("productCategoryList", ['us' => $us, 'pageOn' => 'productCategoryList']);
    }

    //商品分类管理->添加
    public function productadd(Request $request)
    {
        echo 'no';
    }

    //商品分类管理->添加
    public function productdel(Request $request)
    {
        $userid = $request->id;
        $_sql = "delete from product_category where id =" . $userid;
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
        $_sql = "delete from option_info where id=" . $userid;
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
                foreach ($userval as $key => $v) {
                    if ($v['value'] != '') { //如果数据不为空 插入新参数
                        DB::insert('insert into option_value (id, option_id,name) values (?, ?, ?)', [NULL, $opid, $v['value']]);
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
}
