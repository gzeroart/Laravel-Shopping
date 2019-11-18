<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    //用户登录
    public function login()
    {
        return view('login');
    }

    //用户登录验证
    public function userlogin(Request $request)
    {
        //$input = $request->all();
        //接收表单传递过来的数据
        $name = $request->name;
        $passwd = $request->passwd;
        //根据用户名查询数据库信息
        $user = DB::table('user')->where('user_name', $name)->first();
        //验证用户数据是否存在
        if ($user == null) {
            $data['code'] = 204;
            $data['msg'] = '账号密码错误';
            echo json_encode($data);
        } else {
            //验证账号密码
            if ($name === $user->user_name && md5($passwd) === $user->pwd) {
                //保存登录成功状态
                $request->session()->put('isLogin', true);
                //保存登录者账号
                $request->session()->put('isUsername', $name);
                $data['code'] = 200;
                $data['msg'] = '登录成功';
                //$data['sit'] = $request->session()->get('isLogin');
                echo json_encode($data);
            } else {
                $data['code'] = 204;
                $data['msg'] = '账号密码错误';
                echo json_encode($data);
            }
        }
    }
}
