<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>评论管理</title>
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

                    <el-form-item label="商品名" prop="name">
                        <el-col :span="11">
                            <el-input placeholder="请输入商品名" v-model="ruleForm.name"></el-input>
                        </el-col>
                    </el-form-item>


                    <el-form-item label="开始时间" required>
                        <el-col :span="11">
                            <el-form-item prop="date1">
                                <el-date-picker type="date" placeholder="选择日期" v-model="ruleForm.date1" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="2" style="text-align: center;">至</el-col>
                        <el-col :span="11">
                            <el-form-item prop="date2">
                                <el-date-picker type="date" placeholder="选择日期" v-model="ruleForm.date2" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-form-item>

                    <el-form-item>
                        <el-button type="success" @click="queryInfo">查询</el-button>
                        <el-button type="success" @click="resetForm('ruleForm')">重置</el-button>
                        <el-button type="success" @click="examineAll(1)">全部通过</el-button>
                        <el-button type="success" @click="examineAll(0)">全部驳回</el-button>
                        <el-button type="success" @click="delAll('','all')">全部删除</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="main-table">
                <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" @selection-change="handleSelectionChange" border>
                    <el-table-column type="selection" width="55"></el-table-column>
                    <el-table-column fixed prop="name" label="用户名" width="200">
                    </el-table-column>
                    <el-table-column fixed prop="tradeName" label="商品名" width="200">
                    </el-table-column>
                    <el-table-column prop="content" label="内容">
                    </el-table-column>
                    <el-table-column prop="StarRated" label="评论星级" width="200">
                    </el-table-column>
                    <el-table-column prop="time" label="创建时间" width="200">
                    </el-table-column>
                    <el-table-column prop="state" label="审核状态" width="200">
                    </el-table-column>

                    <el-table-column prop="cid" label="操作" width="300">
                        <template slot-scope="scope">
                            <el-button type="primary" size="small" @click="examineRow(scope.row,1)">通过</el-button>
                            <el-button type="primary" size="small" @click="examineRow(scope.row,0)">驳回</el-button>
                            <el-button type="warning" size="small" @click="delAll(scope.row,'one')">删除</el-button>
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
        const comm = new Vue({
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
                        name: '', //商品名
                        date1: '', //更新时间  开始
                        date2: '', //更新时间  结束

                    },

                    tableData: [
                        @foreach($us as $key => $dat) {
                            cid: "{{$key}}",
                            id: "{{$dat['id']}}", //ID
                            name: "{{$dat['name']}}", //用户名
                            tradeName: "{{$dat['tradeName']}}", //商品名
                            content: '{{$dat["content"]}}', //内容
                            StarRated: "{{$dat['StarRated']}}", //评论星级
                            time: "{{$dat['time']}}", //创建时间
                            state: "{{$dat['state']}}", //审核状态
                        },
                        @endforeach
                    ],
                    currentPage: 1,
                    pagesize: 10,
                    multipleSelection: []
                };

            },
            methods: {
                delAll(_this, _type) {
                    //记录选中的值
                    var tabData = comm.multipleSelection;
                    //选中数量
                    var tabnum = comm.multipleSelection.length;
                    if (tabnum > 0 || _type == 'one') {
                        //判断全选删除和单独删除
                        if (_type == 'all') {
                            _this = comm.multipleSelection;
                        }
                        this.$confirm('确定删除吗?', '提示', {
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            type: 'warning'
                        }).then(() => {
                            $.ajax({
                                type: "post",
                                url: "comment/del",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    data: _this,
                                    audit: _type
                                },
                                success: function(data) {
                                    if (data.code == 200) {
                                        comm.tableData = data.data;
                                        comm.$message({
                                            message: data.msg,
                                            type: 'success',
                                            duration: 3000
                                        });
                                    } else {
                                        comm.$message({
                                            message: data.msg,
                                            type: 'warning',
                                            duration: 3000
                                        });
                                    }
                                },
                                error: function(XMLResponse) {
                                    comm.$message.error({
                                        message: '服务器连接失败',
                                        duration: 2000
                                    });
                                }
                            });
                        }).catch(() => {

                        });
                    } else {
                        comm.$message.error({
                            message: '请选择需要删除的评论',
                            duration: 2000
                        });
                    }
                },
                //删除
                deleteRow(index, rows) {
                    rows.splice(index, 1);
                },
                examineAll(_type) {
                    //记录选中的值
                    var tabData = comm.multipleSelection;
                    //选中数量
                    var tabnum = comm.multipleSelection.length;
                    if (tabnum > 0) {
                        $.ajax({
                            type: "post",
                            url: "comment/mods",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                data: comm.multipleSelection,
                                audit: _type
                            },
                            success: function(data) {
                                // console.log(data);
                                if (data.code == 200) {
                                    if (data.res != '') {
                                        for (let i = 0; i < tabnum; i++) {
                                            comm.tableData[tabData[i]['cid']].state = data.res;
                                        }
                                    }
                                    comm.$message({
                                        message: data.msg,
                                        type: 'success',
                                        duration: 3000
                                    });
                                } else {
                                    comm.$message({
                                        message: data.msg,
                                        type: 'warning',
                                        duration: 3000
                                    });
                                }
                            },
                            error: function(XMLResponse) {
                                comm.$message.error({
                                    message: '服务器连接失败',
                                    duration: 2000
                                });
                            }
                        });
                    } else {
                        comm.$message.error({
                            message: '请选择需要审核的评论',
                            duration: 2000
                        });
                    }
                },
                //多选择
                handleSelectionChange(val) {
                    this.multipleSelection = val;
                },
                //通过row
                examineRow(_this, _type) {
                    this.$confirm('确定审核吗?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        $.ajax({
                            type: "post",
                            url: "comment/mod",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: _this.id,
                                audit: _type
                            },
                            success: function(data) {
                                if (data.code == 200) {
                                    if (data.res != '') {
                                        _this.state = data.res;
                                    }
                                    comm.$message({
                                        message: data.msg,
                                        type: 'success',
                                        duration: 3000
                                    });
                                } else {
                                    comm.$message({
                                        message: data.msg,
                                        type: 'warning',
                                        duration: 3000
                                    });
                                }
                            },
                            error: function(XMLResponse) {
                                comm.$message.error({
                                    message: '服务器连接失败',
                                    duration: 2000
                                });
                            }
                        });
                    }).catch(() => {

                    });
                },
                //查询
                queryInfo() {
                    $.ajax({
                        type: "post",
                        url: "comment/qus",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            name: comm.ruleForm.name,
                            date1: comm.ruleForm.date1, //时间  开始
                            date2: comm.ruleForm.date2, //时间  结束
                        },
                        success: function(data) {
                            if (data.code == 200) {
                                comm.$message({
                                    message: data.msg,
                                    type: 'success',
                                    duration: 3000
                                });
                            } else {
                                comm.$message({
                                    message: data.msg,
                                    type: 'warning',
                                    duration: 3000
                                });
                            }
                            comm.tableData = data.data;
                        },
                        error: function(XMLResponse) {
                            comm.$message.error({
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