<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>用户管理</title>
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
</style>

<body>


    <div id="app">
        <!-- 顶部 -->
        <div class="header">
            <div class="collapse-btn" @click="collapseChage">
                <i v-if="!collapse" class="el-icon-s-fold"></i>
                <i v-else class="el-icon-s-unfold"></i>
            </div>
            <h3>商城后台</h3>
            <div class="header-out">
                <i class="el-icon-switch-button "></i>
            </div>
        </div>
        <!-- 菜单 -->
        <div class="menu menu-active">
            <el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b" :collapse="collapse" unique-opened>
                <el-submenu style="padding-top: 80px;" index="1">
                    <template slot="title"> <i class="el-icon-goods"></i><span>商品管理</span></template>
                    <el-menu-item-group>
                        <el-menu-item index="1-1">商品分类管理</el-menu-item>
                        <el-menu-item index="1-2">商品管理</el-menu-item>
                        <el-menu-item index="1-3">Option管理</el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
                <el-submenu index="2">
                    <template slot="title"> <i class="el-icon-document"></i> <span slot="title">订单管理</span></template>
                    <el-menu-item-group>
                        <el-menu-item index="2-1">查看订单</el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
                <el-menu-item index="3" style="color: rgb(255, 208, 75);">
                    <!-- disabled -->
                    <i class="el-icon-user"></i>
                    <span slot="title">用户管理</span>
                </el-menu-item>
                <el-menu-item index="4">
                    <i class="el-icon-s-comment"></i>
                    <span slot="title">评论管理</span>
                </el-menu-item>
                <el-submenu index="5">
                    <template slot="title"> <i class="el-icon-document-copy"></i><span>文章管理</span></template>
                    <el-menu-item-group>
                        <el-menu-item index="5-1">分类管理</el-menu-item>
                        <el-menu-item index="5-2">文章管理</el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
                <el-menu-item index="6">
                    <i class="el-icon-collection"></i>
                    <span slot="title">电子钱包初始金额设定</span>
                </el-menu-item>
            </el-menu>
        </div>

        <!-- 主体内容 -->
        <div class="main">
            <div class="main-form">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="130px" class="demo-ruleForm">

                    <el-form-item label="用户名" prop="username">
                        <el-input placeholder="请输入用户名" style="width: 220px;" v-model="ruleForm.username"></el-input>
                    </el-form-item>
                    <el-form-item label="用户状态" prop="enabled">
                        <el-select v-model="ruleForm.enabled" placeholder="请选择状态">
                            <el-option label="全部" value="all"></el-option>
                            <el-option label="激活用户" value="1"></el-option>
                            <el-option label="冻结用户" value="0"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="注册时间" required>
                        <el-col :span="5">
                            <el-form-item prop="registerTimeForm">
                                <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="ruleForm.registerTimeForm"></el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="5">
                            <el-form-item prop="registerTimeTo">
                                <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="ruleForm.registerTimeTo"></el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="最后登录时间" required>
                        <el-col :span="5">
                            <el-form-item prop="lastLoginTimeForm">
                                <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="ruleForm.lastLoginTimeForm"></el-date-picker>
                            </el-form-item>
                        </el-col>

                        <el-col :span="5">
                            <el-form-item prop="lastLoginTimeTo">
                                <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="ruleForm.lastLoginTimeTo"></el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="用户角色" prop="role">
                        <el-select v-model="ruleForm.role" placeholder="请选择角色">
                            <el-option label="全部" value="all"></el-option>
                            <el-option label="管理员" value="ROLE_ADMIN"></el-option>
                            <el-option label="普通用户" value="ROLE_USER"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="queryInfo">查询</el-button>
                        <el-button @click="resetForm('ruleForm')">重置</el-button>
                        <el-button type="primary" @click="addUser">添加管理员</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="main-table">
                <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" border>
                    <el-table-column fixed prop="username" label="用户名" width="150">
                    </el-table-column>
                    <el-table-column prop="nickname" label="昵称" width="150">
                    </el-table-column>
                    <el-table-column prop="email" label="邮箱">
                    </el-table-column>
                    <el-table-column prop="enabled" label="用户状态" width="120">
                    </el-table-column>
                    <el-table-column prop="role" label="角色" width="150">
                    </el-table-column>
                    <el-table-column prop="lastLoginTime" label="最后登陆时间" width="200">
                    </el-table-column>
                    <el-table-column prop="registerTime" label="注册时间" width="200">
                    </el-table-column>
                    <el-table-column prop="edit" label="操作" width="96">
                        <template slot-scope="scope">
                            <el-button @click="userEdit(scope.row)">编辑</el-button>
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
                    collapse: false,
                    pickerOptions: {
                        disabledDate(time) {
                            return time.getTime() > Date.now();
                        },
                    },
                    value1: '',
                    value2: '',
                    input: '',
                    value: '',
                    ruleForm: {
                        username: '',
                        role: 'all',
                        registerTimeForm: '',
                        registerTimeTo: '',
                        lastLoginTimeForm: '',
                        lastLoginTimeTo: '',
                        enabled: 'all',
                        delivery: false,
                        type: [],
                        resource: '',
                        desc: ''
                    },
                    rules: {
                        region: [{
                            required: true,
                            message: '请选择',
                            trigger: 'change'
                        }],
                    },
                    tableData: [
                        @foreach($us as $key => $dat) {
                            username: "{{$dat['username']}}",
                            nickname: "{{$dat['nickname']}}",
                            email: "{{$dat['email']}}",
                            enabled: "{{$dat['enabled']}}",
                            role: "{{$dat['role']}}",
                            lastLoginTime: "{{$dat['lastLoginTime']}}",
                            registerTime: "{{$dat['registerTime']}}",
                            edit: "{{$dat['edit']}}",
                        },
                        @endforeach
                    ],
                    currentPage: 1,
                    pagesize: 10
                };

            },
            methods: {
                addUser() {
                    window.location = './user/add';
                },
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
                handleOpen(key, keyPath) {
                    console.log(key, keyPath);
                },
                handleClose(key, keyPath) {
                    console.log(key, keyPath);
                },
                // 侧边栏折叠
                collapseChage() {
                    //this.collapse = !this.collapse;
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
</body>

</html>