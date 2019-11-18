<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>用户信息修改</title>
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
                <el-menu-item index="3">
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
                    <el-form-item label="管理员名" prop="name1">
                        <el-input placeholder="请输入管理员名" v-model="ruleForm.name1"></el-input>
                    </el-form-item>
                    <el-form-item label="昵称" prop="name2">
                        <el-input placeholder="请输入昵称" v-model="ruleForm.name2"></el-input>
                    </el-form-item>
                    <el-form-item label="密码" prop="password1">
                        <el-input placeholder="请输入密码" v-model="ruleForm.password1" show-password></el-input>
                    </el-form-item>
                    <el-form-item label="确认密码" prop="password2">
                        <el-input placeholder="请输入密码" v-model="ruleForm.password2" show-password></el-input>
                    </el-form-item>
                    <el-form-item label="邮箱" prop="email">
                        <el-input placeholder="请输入邮箱" v-model="ruleForm.email"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-radio disabled v-model="radio" label="ROLE_ADMIN">管理员</el-radio>
                        <el-radio disabled v-model="radio" label="ROLE_USER">一般用户</el-radio>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">保存</el-button>
                        <el-button type="primary" onclick="window.history.go(-1)">返回</el-button>
                    </el-form-item>
                </el-form>
            </div>

        </div>


    </div>
    <script src="{{asset('asset/admin/jquery3-4-1/jquery.min.js')}}"></script>
    <script>
        const useredit = new Vue({
            el: '#app',
            data() {
                var validatePass = (rule, value, callback) => {
                    if (value === '') {
                        callback(new Error('请输入密码'));
                    } else {
                        if (this.ruleForm.password2 !== '') {
                            this.$refs.ruleForm.validateField('password2');
                        }
                        callback();
                    }
                };
                var validatePass2 = (rule, value, callback) => {
                    if (value === '') {
                        callback(new Error('请再次输入密码'));
                    } else if (value !== this.ruleForm.password1) {
                        callback(new Error('两次输入密码不一致!'));
                    } else {
                        callback();
                    }
                };
                return {
                    collapse: false,
                    input: '',
                    value: '',
                    ruleForm: {
                        name1: '{{$usertitle}}',
                        name2: '{{$username}}',
                        password1: '',
                        password2: '',
                        email: '{{$useremail}}',
                        id: '{{$userid}}'
                    },
                    rules: {
                        password1: [{
                            validator: validatePass,
                            trigger: 'blur'
                        }],
                        password2: [{
                            validator: validatePass2,
                            trigger: 'blur'
                        }],
                        region: [{
                            required: true,
                            message: '请选择',
                            trigger: 'change'
                        }],

                    },
                    radio: '{{$userrole}}'
                };

            },
            methods: {
                onSubmit() {
                    $.ajax({
                        type: "post",
                        url: "editUser",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: useredit.ruleForm,
                        success: function(data) {
                            if (data.code == 200) {
                                useredit.$message({
                                    message: data.msg,
                                    type: 'success',
                                    duration: 1500,
                                    onClose: function() {
                                        window.location = './';
                                    }
                                });
                            } else {
                                useredit.$message({
                                    message: data.msg,
                                    type: 'warning',
                                    duration: 3000
                                });
                            }
                        },
                        error: function(XMLResponse) {
                            useredit.$message.error({
                                message: '服务器连接失败',
                                duration: 3000
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