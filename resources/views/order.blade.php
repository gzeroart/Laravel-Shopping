<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>订单管理——查看订单</title>
    <link rel="stylesheet" href="{{asset('asset/admin/element-ui/lib/theme-chalk/index.css')}}">
    <script src="{{asset('asset/admin/vue/vue.js')}}"></script>
    <script src="{{asset('asset/admin/element-ui/lib/index.js')}}"></script>

</head>
<style>
    * {
        padding: 0;
        margin: 0;
    }

    #app {
        width: 100%;
        height: 100vh;
    }

    .header {
        width: 100%;
        border-bottom: 1px solid rgb(160, 160, 160);
        background-color: rgb(84, 92, 100);
        color: azure;
        position: absolute;
        top: 0;
        z-index: 1000;
    }

    .header h3 {
        display: inline;
    }

    .collapse-btn {
        padding: 21px;
        cursor: pointer;
        line-height: 70px;
        display: inline;
        font-size: 20px;
    }

    .header-out {
        font-size: 20px;
        line-height: 70px;
        padding: 0 21px;
        display: block;
        float: right;
    }

    .el-menu-vertical-demo:not(.el-menu--collapse) {
        width: 250px;
        height: 100%;
        min-height: 400px;
    }

    .el-menu--collapse {
        height: 100%;
    }

    .menu {
        height: 100%;
        float: left;
        transition: all .5s;
        position: absolute;
        left: -251px;
    }

    .menu-active {
        left: 0;
    }

    .main {
        padding: 20px;
        padding-top: 91px;
        width: calc(100% - 291px);
        float: right;
        background: rgb(241, 243, 246);
        height: calc(100vh - 111px);
        overflow-y: auto;
        transition: all .5s;
    }

    .main-active {
        width: calc(100% - 40px);
    }

    @media screen and (max-width: 450px) {
        .el-menu--collapse {
            left: -80px;
        }

        .menu {
            width: 0;
            left: -100%;
        }

        .main {
            width: calc(100% - 40px) !important;
        }
    }

    @media screen and (max-width: 768px) {
        .menu {
            width: 100%;
            left: 0;
        }

        .menu-active {
            left: -100%;
        }

        .el-menu-vertical-demo:not(.el-menu--collapse) {
            width: 100%;
        }
    }

    .control {
        background: #fff;
        padding: 20px;
        border: 1px solid #e4e5e6;
    }

    .control label {
        display: block;
        color: #000;
    }

    .main-form {
        width: 97%;
        background-color: rgb(255, 255, 255);
        padding: 20px;
    }

    .main-table {
        width: 97%;
        background-color: rgb(255, 255, 255);
        padding: 20px;
        margin-top: 20px;
    }

    .el-date-editor.el-input,
    .el-date-editor.el-input__inner {
        width: 100%;
    }
</style>

<body>
    @include('nav')
    <div id="app">
        <!-- 主体内容 -->
        <div class="main">
            <div class="main-form">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="130px" class="demo-ruleForm">

                    <el-form-item label="订单编号" prop="username">
                        <el-col :span="13">
                            <el-input placeholder="请输入订单编号" v-model="ruleForm.username"></el-input>
                        </el-col>
                    </el-form-item>

                    <el-form-item label-width="0px">
                        <el-col :span="7">
                            <el-form-item label="付款标志" prop="enabled">
                                <el-select v-model="ruleForm.enabled" placeholder="请选择状态">
                                    <el-option label="全部" value="all"></el-option>
                                    <el-option label="未付" value="0"></el-option>
                                    <el-option label="已付" value="1"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="7">
                            <el-form-item label="订单状态" prop="role">
                                <el-select v-model="ruleForm.role" placeholder="请选择状态">
                                    <el-option label="全部" value="all"></el-option>
                                    <el-option label="订单编辑中" value="0"></el-option>
                                    <el-option label="已下单" value="1"></el-option>
                                    <el-option label="配送中" value="2"></el-option>
                                    <el-option label="配送完成" value="3"></el-option>
                                    <el-option label="订单取消" value="4"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-form-item>

                    <el-form-item label="总价" required>
                        <el-col :span="6">
                            <el-form-item prop="price1">
                                <el-input placeholder="请输入价格" v-model="ruleForm.price1"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="1" style="text-align: center;">至</el-col>
                        <el-col :span="6">
                            <el-form-item prop="price2">
                                <el-input placeholder="请输入价格" v-model="ruleForm.price2"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-form-item>

                    <el-form-item label="下单时间">
                        <el-col :span="6">
                            <el-form-item prop="lastLoginTimeForm">
                                <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="ruleForm.lastLoginTimeForm"></el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="1" style="text-align: center;">至</el-col>
                        <el-col :span="6">
                            <el-form-item prop="lastLoginTimeTo">
                                <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="ruleForm.lastLoginTimeTo"></el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-form-item>

                    <el-form-item>
                        <el-button type="primary" @click="queryInfo">查询</el-button>
                        <el-button @click="resetForm('ruleForm')">重置</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="main-table">
                <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" border>
                    <el-table-column fixed prop="username" label="订单编号">
                    </el-table-column>
                    <el-table-column prop="nickname" label="总价" width="150">
                    </el-table-column>
                    <el-table-column prop="email" label="付款标志" width="120">
                    </el-table-column>
                    <el-table-column prop="enabled" label="订单状态" width="130">
                    </el-table-column>
                    <el-table-column prop="role" label="联系人" width="150">
                    </el-table-column>
                    <el-table-column prop="lastLoginTime" label="联系电话" width="250">
                    </el-table-column>
                    <el-table-column prop="registerTime" label="订单时间" width="250">
                    </el-table-column>
                    <el-table-column prop="edit" label="操作" width="96">
                        <template slot-scope="scope">
                            <el-button @click="orderEdit(scope.row)">查看</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="pagesize" :total="tableData.length" layout="total, sizes, prev, pager, next, jumper">
                </el-pagination>

            </div>
        </div>


    </div>
    <script src="{{asset('asset/admin/jquery3-4-1/jquery.min.js')}}"></script>

    <script>
        const order = new Vue({
            el: '#app',
            data() {
                return {
                    value: '',
                    ruleForm: {
                        username: '', //订单编号
                        role: 'all', //订单状态
                        lastLoginTimeForm: '', //开始时间
                        lastLoginTimeTo: '', //结束时间
                        enabled: 'all', //付款标志
                        delivery: false,
                        type: [],
                        price1: '', //总价开始
                        price2: '', //总价结束
                    },
                    rules: {
                        region: [{
                            required: true,
                            message: '请选择',
                            trigger: 'change'
                        }],
                    },
                    pageOn: '{{$pageOn}}',
                    tableData: [
                        @foreach($us as $key => $dat) {
                            username: "{{$dat['order_num']}}", //订单编号
                            nickname: "{{$dat['price']}}", //总价
                            email: "{{$dat['payment_flag']}}", //付款标志
                            enabled: "{{$dat['status']}}", //订单状态
                            role: "{{$dat['contact_name']}}", //联系人
                            lastLoginTime: "{{$dat['contact_mobile']}}", //联系电话
                            registerTime: "{{$dat['create_time']}}", //订单时间
                            edit: "{{$key}}", //查看
                        },
                        @endforeach
                    ],
                    currentPage: 1,
                    pagesize: 10
                };

            },
            methods: {

                orderEdit(_this) {
                    window.location = './order/' + _this.username;
                },
                queryInfo() {
                    $.ajax({
                        type: "post",
                        url: "order/qus",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: order.ruleForm,
                        success: function(data) {
                            //console.log(data);
                            if (data.code == 200) {
                                order.$message({
                                    message: data.msg,
                                    type: 'success',
                                    duration: 3000
                                });
                            } else {
                                order.$message({
                                    message: data.msg,
                                    type: 'warning',
                                    duration: 3000
                                });
                            }
                            order.tableData = data.data;
                        },
                        error: function(XMLResponse) {
                            order.$message.error({
                                message: '服务器连接失败',
                                duration: 2000
                            });
                        }
                    });
                },


                submitForm(formName) {
                    this.$refs[formName].validate((valid) => {
                        if (valid) {
                            alert('submit!');
                        } else {
                            console.log('error submit!!');
                            return false;
                        }
                    });
                },
                //重置
                resetForm(formName) {
                    this.$refs[formName].resetFields();
                },
                handleSizeChange: function(val) {
                    this.pagesize = val;
                },
                handleCurrentChange: function(currentPage) {
                    this.currentPage = currentPage;
                },

            }
        })

        $(document).ready(function() {
            var mediaWidth = $(window).width(); //屏幕视口宽度
            // console.log(mediaWidth);

            if ($('.menu').css('left') != '0px') {
                $('.collapse-btn>i').attr('class', 'el-icon-s-unfold'); //更改移动端默认菜单按钮
            }

            function ChangeMenuIcon() { //更改菜单按钮
                if ($('.collapse-btn>i').attr('class') == 'el-icon-s-fold') {
                    $('.collapse-btn>i').attr('class', 'el-icon-s-unfold');
                } else {
                    $('.collapse-btn>i').attr('class', 'el-icon-s-fold');
                }
            }

            var menuActive = true;
            $('.collapse-btn').click(function() {
                if (menuActive == true) {
                    $('.menu').removeClass('menu-active');
                    $('.main').addClass('main-active');
                    ChangeMenuIcon();
                    menuActive = false;
                } else {
                    $('.menu').addClass('menu-active');
                    $('.main').removeClass('main-active');
                    ChangeMenuIcon();
                    menuActive = true;
                }
            })
        })
    </script>
    <!-- 菜单 -->
    <script src="{{asset('asset/admin/menu.js')}}"></script>
</body>

</html>