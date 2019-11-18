<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>文章管理-分类管理</title>
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
                        <el-menu-item index="5-1" style="color: rgb(255, 208, 75);">分类管理</el-menu-item>
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
                <el-form :model="ruleForm" ref="ruleForm" label-width="130px" class="demo-ruleForm">

                    <el-form-item label="更新时间" required>
                        <el-col :span="11">
                            <el-form-item prop="date1">
                                <el-date-picker type="date" placeholder="选择日期" v-model="ruleForm.date1"></el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="2" style="text-align: center;">至</el-col>
                        <el-col :span="11">
                            <el-form-item prop="date2">
                                <el-date-picker type="date" placeholder="选择日期" v-model="ruleForm.date2"></el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="分类名" prop="name">
                        <el-col :span="11">
                            <el-input placeholder="请输入分类名" v-model="ruleForm.name"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary">查询</el-button>
                        <el-button type="info" @click="resetForm('ruleForm')">重置</el-button>
                        <el-button type="primary" @click="open">新增</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="main-table">
                <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" border>
                    <el-table-column fixed prop="date" label="分类名" width="200">
                    </el-table-column>
                    <el-table-column prop="province" label="最后更新时间">
                    </el-table-column>
                    <el-table-column prop="name" label="更新者名" width="200">
                    </el-table-column>

                    <el-table-column prop="edit" label="操作" width="200">
                        <template slot-scope="scope">
                            <el-button type="primary" @click="open2" size="small">编辑</el-button>
                            <el-button type="warning" @click.native.prevent="deleteRow(scope.$index, tableData,scope.row)" size="small">删除</el-button>
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
        const sort = new Vue({
            el: '#app',
            data() {
                return {
                    collapse: false,
                    pickerOptions: {
                        disabledDate(time) {
                            return time.getTime() > Date.now();
                        },
                    },
                    value: '',
                    ruleForm: {
                        name: '', //分类名
                        date1: '', //更新时间  开始
                        date2: '', //更新时间  结束
                        delivery: false,
                        type: [],
                    },

                    tableData: [
                        @foreach($us as $key => $dat) {
                            date: '{{$dat["title"]}}', //分类名
                            name: '{{$dat["name"]}}', //更新者名
                            province: '{{$dat["time"]}}', //最后更新时间
                            edit: '{{$dat["id"]}}' //ID
                        },
                        @endforeach
                    ],
                    currentPage: 1,
                    pagesize: 10
                };

            },
            methods: {
                //侧边栏
                handleOpen(key, keyPath) {
                    //console.log(key, keyPath);
                },
                handleClose(key, keyPath) {
                    // console.log(key, keyPath);
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
                //重置按钮
                resetForm(formName) {
                    this.$refs[formName].resetFields();
                },
                //分页
                handleSizeChange: function(val) {
                    this.pagesize = val;
                },
                handleCurrentChange: function(currentPage) {
                    this.currentPage = currentPage;
                },
                deleteRow(index, rows, _this) {
                    this.$confirm('是否删除此分类？', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        $.ajax({
                            type: "post",
                            url: "../sort/del",
                            //dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: _this.edit
                            },
                            success: function(data) {
                                if (datalen != 0) {
                                    sort.$message({
                                        message: data,
                                        type: 'success',
                                        duration: 1500
                                    });
                                } else {
                                    sort.$message({
                                        message: data,
                                        type: 'warning',
                                        duration: 1500
                                    });
                                }
                            },
                            error: function(XMLResponse) {
                                sort.$message.error({
                                    message: '服务器连接失败',
                                    duration: 2000
                                });
                            }
                        });
                        //rows.splice(index, 1);
                        // this.$message({
                        //     type: 'success',
                        //     message: '删除成功!',
                        //     duration: 1500
                        // });
                    }).catch(() => {

                    });
                    // scope.row
                    console.log(_this.edit);
                    //移除行
                    //rows.splice(index, 1);
                },
                //新增
                open() {
                    this.$prompt('分类名', '修改/新增', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',

                        inputErrorMessage: '分类名不能为空'
                    }).then(({
                        value
                    }) => {
                        this.$message({
                            type: 'success',
                            message: '你的分类名: ' + value
                        });
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '取消输入'
                        });
                    });
                },
                //编辑
                open2() {
                    this.$prompt('分类名', '修改/新增', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',

                        inputErrorMessage: '分类名不能为空'
                    }).then(({
                        value
                    }) => {
                        this.$message({
                            type: 'success',
                            message: '你的分类名: ' + value
                        });
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '取消输入'
                        });
                    });
                }
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