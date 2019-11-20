<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //用户管理
    public function user()
    {
        //查询用户数据表
        $users = DB::table('user')->get();
        //定义一个为空的数组
        $us = array();

        foreach ($users as $k => $user) {
            //合并数组
            array_push($us, array(
                "username" => $user->user_name, //用户名
                "nickname" => $user->nickname, //用户昵称
                "email" => $user->email, //用户邮箱
                "enabled" => $this->enabled($user->enabled), //调用当前页面其他函数获取用户状态
                "role" => $this->userrole($user->user_name), //调用当前页面其他函数获取用户等级
                "lastLoginTime" => $user->last_login_time, //登录时间
                "registerTime" => $user->register_time, //注册时间
                "edit" => $user->id //ID
            ));
        }
        return view('user', ['us' => $us, 'pageOn' => 'user']);
    }

    //判断用户等级
    public function userrole($_where, $_t = 1)
    {
        $user = DB::table('user_roles')->where('username', $_where)->value('role');
        //判断是否输出源数据
        if ($_t == 1) {
            //数据转换
            if ($user == 'ROLE_ADMIN') {
                return '管理员';
            } else {
                return '普通用户';
            }
        } else {
            return $user;
        }
    }

    //判断用户激活状态
    public function enabled($_data)
    {
        if ($_data == 1) {
            return '激活用户';
        } else {
            return '冻结用户';
        }
    }

    //用户信息查询
    public function quserInfo(Request $request)
    {
        $username = $request->username; //接收到用户名
        $userrole = $request->role; //接收用户角色
        $enabled = $request->enabled; //接收用户状态
        $registerTimeForm = $request->registerTimeForm; //注册时间
        $registerTimeTo = $request->registerTimeTo; //注册时间2
        $lastLoginTimeForm = $request->lastLoginTimeForm; //登录时间
        $lastLoginTimeTo = $request->lastLoginTimeTo; //登录时间2
        //判断用户角色
        if ($userrole == 'all') {
            $userrole = '';
        }
        //判断用户状态
        if ($enabled == 'all') {
            $enabled = '';
        }
        //接收到用户名
        if ($username != '') {
            $username = " and T1.user_name like concat('%','" . $username . "','%') ";
        }
        //判断用户角色
        if ($userrole != '') {
            $userrole = " and T2.role = '" . $userrole . "' ";
        }
        //判断用户状态
        if ($enabled != '') {
            $enabled = " and T1.enabled = '" . $enabled . "' ";
        }
        //注册时间
        if ($registerTimeForm != '') {
            $registerTimeForm = " and date_format(T1.register_time,'%Y-%m-%d')>= '" . $registerTimeForm . "' ";
        }
        //注册时间2
        if ($registerTimeTo != '') {
            $registerTimeTo = " and date_format(T1.register_time,'%Y-%m-%d')<= '" . $registerTimeTo . "' ";
        }
        //登录时间
        if ($lastLoginTimeForm != '') {
            $lastLoginTimeForm = " and date_format(T1.last_login_time,'%Y-%m-%d') <= '" . $lastLoginTimeForm . "' ";
        }
        //自定义查询语句
        $_sql = "select T1.id, T1.user_name, T1.pwd, T1.nickname, T1.email, T1.enabled, T1.open_id, T1.oauth_type, T1.image, T1.last_login_time, T1.register_time, T2.role from `user` T1 left join user_roles T2 ON T1.user_name= T2.username where 1=1" . $username . $enabled . $userrole . $registerTimeForm . $registerTimeTo . $lastLoginTimeForm . ";";
        //使用SQL原生模式
        $users = DB::select($_sql);
        if ($users == null) {
            //定义一个用于储存的数组'code' => 204, 'msg' => '无数据'
            $us = array();
            echo json_encode($us);
        } else {
            //定义一个用于储存的数组 'code' => 200, 'msg' => '查询成功'
            $us = array();
            foreach ($users as $k => $user) {
                //合并数组
                array_push($us, array(
                    "username" => $user->user_name, //用户名
                    "nickname" => $user->nickname, //用户昵称
                    "email" => $user->email, //用户邮箱
                    "enabled" => $this->enabled($user->enabled), //调用当前页面其他函数获取用户状态
                    "role" => $this->userrole($user->user_name), //调用当前页面其他函数获取用户等级
                    "lastLoginTime" => $user->last_login_time, //登录时间
                    "registerTime" => $user->register_time, //注册时间
                    "edit" => $user->id //ID
                ));
            }
            echo json_encode($us);
        }
    }

    //用户个人信息
    public  function oneUser(Request $request)
    {
        //获取用户ID
        $userid = $request->id;
        //查询用户信息
        $user = DB::table('user')->where('id', $userid)->first();
        if ($user != null) {
            $usertitle = $user->user_name;
            $username = $user->nickname;
            $useremail = $user->email;
            $userrole = $this->userrole($user->user_name, 0);
            $userid2 = $user->id;
        }
        //dump($user);
        return view('useredit', ['usertitle' => $usertitle, 'username' => $username, 'useremail' => $useremail, 'userrole' => $userrole, 'userid' => $userid2, 'pageOn' => 'user']);
    }

    //用户信息修改
    public function editUser(Request $request)
    {
        $id = $request->id;
        $name1 = $request->name1;
        $name2 = $request->name2;
        $password1 = $request->password1;
        $password2 = $request->password2;
        $email = $request->email;

        //验证密码是否相同 且不为空
        if ($password1 === $password2 && $password1 != '' && $password2 != '') {
            $password = md5($password1);
            $_sql = "update `user` SET id = " . $id . ", user_name = '" . $name1 . "', pwd = '" . $password . "', nickname = '" . $name2 . "', email = '" . $email . "' WHERE ( id = " . $id . " )";
            $user = DB::update($_sql);
            if ($user >= 0) {
                $data['code'] = 200;
                $data['msg'] = '信息修改成功';
                echo json_encode($data);
            } else {
                $data['code'] = 204;
                $data['msg'] = '信息修改失败';
                echo json_encode($data);
            }
        } else {
            $data['code'] = 204;
            $data['msg'] = '信息验证失败';
            echo json_encode($data);
        }
    }

    //添加用户
    public function addUser()
    {
        return view('useradd', ['pageOn' => 'user']);
    }

    //添加新用户
    public function addUserInfo(Request $request)
    {
        $username = $request->name1; //用户名
        $username2 = $request->name2; //昵称
        $password1 = $request->password1; //密码
        $password2 = $request->password2; //密码
        $email = $request->email; //邮箱地址
        $role = $request->role; //角色

        if ($role == 1) {
            $role2 = 'ROLE_ADMIN';
        } else {
            $role2 = 'ROLE_USER';
        }
        if ($password1 === $password2 && $password1 != '' && $password2 != '') {
            //查询用户是否存在
            $user = DB::table('user')->where('user_name', $username)->first();
            if ($user == null) {
                //密码
                $password = md5($password1);
                //校准时间
                date_default_timezone_set("Asia/Shanghai");
                $user_time = date("Y-m-d H:i:s");
                //设置数据库语句
                $_sql = "insert into `user` (id,user_name, pwd, nickname, email, enabled, open_id, oauth_type, image, last_login_time, register_time) values (?,?,?,?,?,?,?,?,?,?,?)";
                //执行数据库插入
                $user2 = DB::insert($_sql, [NULL, $username, $password, $username2, $email, $role, NULL, NULL, NULL, $user_time, $user_time]);
                if ($user2) { //如果插入成功
                    //为新用户添加权限
                    $user3 = DB::insert('insert into user_roles (user_role_id,username,role) values (?,?,?)', [NULL, $username, $role2]);

                    $data['code'] = 200;
                    $data['msg'] = '新用户添加成功';
                    echo json_encode($data);
                } else {
                    $data['code'] = 204;
                    $data['msg'] = '新用户添加失败';
                    echo json_encode($data);
                }
            } else {
                $data['code'] = 204;
                $data['msg'] = '该用户已存在';
                echo json_encode($data);
            }
        }
    }
}
