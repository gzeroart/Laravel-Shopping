<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>文章管理-文章管理</title>
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
                <el-form :model="ruleForm" ref="ruleForm" label-width="130px" class="demo-ruleForm">
                    <el-form-item label-width="0">
                        <el-form-item label="标题" prop="name">
                            <el-col :span="11">
                                <el-input placeholder="请输入标题" v-model="ruleForm.name"></el-input>
                            </el-col>

                            <el-form-item label="文章分类" prop="article" label-width="100px">
                                <el-col :span="11">
                                    <el-input placeholder="请输入文章分类" v-model="ruleForm.article"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-form-item>
                    </el-form-item>
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

                    <el-form-item>
                        <el-button type="primary" @click="queryInfo()">查询</el-button>
                        <el-button type="info" @click="resetForm('ruleForm')">重置</el-button>
                        <el-button type="primary">新增</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="main-table">
                <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" border>
                    <el-table-column fixed prop="title" label="标题" width="200">
                    </el-table-column>
                    <el-table-column fixed prop="date" label="分类名" width="200">
                    </el-table-column>
                    <el-table-column prop="province" label="最后更新时间">
                    </el-table-column>
                    <el-table-column prop="name" label="更新者名" width="200">
                    </el-table-column>

                    <el-table-column prop="edit" label="操作" width="200">
                        <template slot-scope="scope">
                            <el-button type="primary" @click="" size="small">编辑</el-button>
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
        const manage = new Vue({
            el: '#app',
            data() {
                return {
                    collapse: false,
                    value: '',
                    ruleForm: {
                        name: '', //标题
                        article: '', //文章分类
                        date1: '', //更新时间  开始
                        date2: '', //更新时间  结束
                        delivery: false,
                        type: [],
                    },

                    tableData: [
                        @foreach($us as $key => $dat) {
                            id: "{{$dat['id']}}",
                            title: '{{$dat["title"]}}', //标题
                            date: '{{$dat["date"]}}', //分类名
                            name: '{{$dat["name"]}}', //更新者名
                            province: '{{$dat["province"]}}', //最后更新时间
                        },
                        @endforeach
                    ],
                    currentPage: 1,
                    pagesize: 10
                };

            },
            methods: {
                queryInfo() {
                    $.ajax({
                        type: "post",
                        url: "manage/qus",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: manage.ruleForm,
                        success: function(data) {
                            var datalen = data.data.length;
                            if (datalen != 0) {
                                manage.$message({
                                    message: '查询到相关数据' + datalen + '条',
                                    type: 'success',
                                    duration: 3000
                                });
                            } else {
                                manage.$message({
                                    message: '无数据',
                                    type: 'warning',
                                    duration: 3000
                                });
                            }
                            manage.tableData = data.data;
                        },
                        error: function(XMLResponse) {
                            manage.$message.error({
                                message: '服务器连接失败',
                                duration: 2000
                            });
                        }
                    });
                },
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
                //删除
                deleteRow(index, rows, _this) {

                    this.$confirm('是否要删除文章?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        $.ajax({
                            type: "post",
                            url: "manage/del",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: _this.id
                            },
                            success: function(data) {
                                if (data.code == 200) {
                                    rows.splice(index, 1);
                                    manage.$message({
                                        message: data.msg,
                                        type: 'success',
                                        duration: 3000
                                    });
                                } else {
                                    manage.$message({
                                        message: data.msg,
                                        type: 'warning',
                                        duration: 3000
                                    });
                                }
                            },
                            error: function(XMLResponse) {
                                manage.$message.error({
                                    message: '服务器连接失败',
                                    duration: 2000
                                });
                            }
                        });
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
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
    <!-- 菜单 -->
    <script src="{{asset('asset/admin/menu.js')}}"></script>
</body>

</html>