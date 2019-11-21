<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品管理——商品管理</title>
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

                    <el-form-item label="商品名" prop="username">
                        <el-col :span="13">
                            <el-input placeholder="请输入商品名" v-model="ruleForm.username"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="热门商品" prop="tradeName">
                        <el-radio-group v-model="ruleForm.tradeName">
                            <el-radio :label="3">全部</el-radio>
                            <el-radio :label="6">热门商品</el-radio>
                            <el-radio :label="9">非热门商品</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label-width="0px">
                        <el-col :span="7">
                            <el-form-item label="商品分类" prop="role">
                                <el-select v-model="ruleForm.role" placeholder="请选择状态">
                                    <el-option label="全部" value="all"></el-option>
                                    <el-option label="水果类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="蔬菜蛋类" value="ROLE_USER"></el-option>
                                    <el-option label="肉禽类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="水果类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="测试分类" value="ROLE_USER"></el-option>
                                    <el-option label="男装" value="ROLE_ADMIN"></el-option>
                                    <el-option label="女装" value="ROLE_ADMIN"></el-option>
                                    <el-option label="童装" value="ROLE_USER"></el-option>
                                    <el-option label="运动鞋类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="休闲类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="家电" value="ROLE_USER"></el-option>
                                    <el-option label="家居" value="ROLE_ADMIN"></el-option>
                                    <el-option label="鞋帽类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="电子类" value="ROLE_USER"></el-option>
                                    <el-option label="软件类" value="ROLE_ADMIN"></el-option>
                                    <el-option label="电脑类" value="ROLE_USER"></el-option>
                                    <el-option label="家电类" value="ROLE_ADMIN"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="7">
                            <el-form-item label="状态" prop="enabled" label-width="80px">
                                <el-select v-model="ruleForm.enabled" placeholder="请选择状态">
                                    <el-option label="全部" value="all"></el-option>
                                    <el-option label="已上架" value="1"></el-option>
                                    <el-option label="已下架" value="0"></el-option>
                                </el-select>
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
                        <el-button type="primary">添加</el-button>
                        <el-button type="primary" @click="queryInfo">查询</el-button>
                        <el-button @click="resetForm('ruleForm')">重置</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="main-table">
                <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" border>
                    <el-table-column fixed prop="username" label="商品名" width="150">
                    </el-table-column>
                    <el-table-column prop="nickname" label="商品分类" width="150">
                    </el-table-column>
                    <el-table-column prop="email" label="商品概要说明">
                    </el-table-column>
                    <el-table-column prop="enabled" label="店内价格" width="130">
                    </el-table-column>
                    <el-table-column prop="role" label="市场价格" width="150">
                    </el-table-column>
                    <el-table-column prop="lastLoginTime" label="数量" width="100">
                    </el-table-column>
                    <el-table-column prop="popular" label="热门商品" width="150">
                    </el-table-column>
                    <el-table-column prop="state" label="状态" width="120">
                    </el-table-column>
                    <el-table-column prop="registerTime" label="更新时间" width="250">
                    </el-table-column>
                    <el-table-column prop="edit" label="操作" width="96">
                        <template slot-scope="scope">
                            <el-button @click="userEdit(scope.row)">查看</el-button>
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
        const user = new Vue({
            el: '#app',
            data() {
                return {
                    value: '',
                    ruleForm: {
                        username: '', //商品名
                        role: 'all', //商品分类
                        lastLoginTimeForm: '', //下单开始时间
                        lastLoginTimeTo: '', //下单结束时间
                        enabled: 'all', //状态
                        tradeName: '', //热门商品
                        delivery: false,
                        type: [],
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
                            username: "{{$dat['username']}}", //商品名
                            nickname: "{{$dat['nickname']}}", //商品分类
                            email: "{{$dat['email']}}", //商品概要说明
                            enabled: "{{$dat['enabled']}}", //店内价格
                            role: "{{$dat['role']}}", //市场价格
                            lastLoginTime: "{{$dat['lastLoginTime']}}", //数量
                            popular: "{{$dat['lastLoginTime']}}" //热门商品
                            state: "{{$dat['lastLoginTime']}}" //状态
                            registerTime: "{{$dat['registerTime']}}", //更新时间
                            edit: "{{$dat['edit']}}", //查看
                        },
                        @endforeach
                    ],
                    currentPage: 1,
                    pagesize: 10
                };

            },
            methods: {

                userEdit(_this) {
                    window.location = './user/' + _this.edit;
                },
                queryInfo() {
                    $.ajax({
                        type: "post",
                        url: "quserInfo",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            username: user.ruleForm.username,
                            role: user.ruleForm.role,
                            registerTimeForm: user.ruleForm.registerTimeForm,
                            registerTimeTo: user.ruleForm.registerTimeTo,
                            lastLoginTimeForm: user.ruleForm.lastLoginTimeForm,
                            lastLoginTimeTo: user.ruleForm.lastLoginTimeTo,
                            enabled: user.ruleForm.enabled
                        },
                        success: function(data) {
                            var datalen = data.length;
                            if (datalen != 0) {
                                user.$message({
                                    message: '查询到相关数据' + datalen + '条',
                                    type: 'success',
                                    duration: 3000
                                });
                            } else {
                                user.$message({
                                    message: '无数据',
                                    type: 'warning',
                                    duration: 3000
                                });
                            }
                            user.tableData = data;
                        },
                        error: function(XMLResponse) {
                            user.$message.error({
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