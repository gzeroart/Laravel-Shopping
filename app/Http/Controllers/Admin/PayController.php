<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PayController extends Controller
{
    //添加金额
    public function payadd(Request $request)
    {
        $userval = $request->val;
        //校准时间
        date_default_timezone_set("Asia/Shanghai");
        $user_time = date("Y-m-d H:i:s"); //时间
        $user = DB::insert('INSERT INTO ewallet (id,amount,updtime) VALUES (?,?,?)', [NULL, $userval, $user_time]);
        if ($user) {
            $data['code'] = 200;
            $data['msg'] = '保存成功';
            echo json_encode($data);
        } else {
            $data['code'] = 204;
            $data['msg'] = '操作失败';
            echo json_encode($data);
        }
    }

    public function pay()
    {
        return view('pay', ['pageOn' => 'pay']);
    }
}
