<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查看订单-查看</title>
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

    /*订单编号*/

    .title {
        width: 100%;
        height: 50px;
        border-bottom: 1.5px solid rgb(216, 216, 216);
    }

    .title h2 {
        float: left;
    }

    .title p {
        float: left;
        line-height: 31px;
    }

    .title button {
        float: right;
    }

    /*订单状态*/

    .state {
        width: 100%;
        height: 40px;
    }

    .state h3 {
        float: left;
    }

    .state p {
        float: left;
        line-height: 25px;
    }

    .state span {
        float: right;
        line-height: 25px;
        color: rgb(194, 194, 194);
        font-size: 14px;
    }

    .dialog-footer {
        right: 22px;
        bottom: 20px;
        position: absolute;
    }

    .el-button {
        margin-left: 20px;
    }
</style>

<body>
    @include('nav')
    <div id="app">
        <!-- 主体内容 -->
        <div class="main">
            <div class="main-form">
                <div class="title">
                    <h2>订单编号：</h2>
                    <p>{{$dat['info']['username']}}</p>
                    <el-button type="primary" icon="el-icon-share" size="small " @click="dialogFormVisible = true">物流配送</el-button>
                    <el-dialog title="收货地址" :visible.sync="dialogFormVisible">
                        <el-form :model="form">
                            <el-form-item label="物流公司名" :label-width="formLabelWidth">
                                <el-input v-model="form.name" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item label="配送状态：" :label-width="formLabelWidth">
                                <el-select v-model="form.region" placeholder="请选择活动区域">
                                    <el-option label="配送" value="2"></el-option>
                                    <el-option label="配送完了" value="3"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                        <div slot="footer" class="dialog-footer">
                            <el-button @click="dialogFormVisible = false">取 消</el-button>
                            <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
                        </div>
                    </el-dialog>
                </div>
                <div style="padding: 10px 20px; color: rgb(114, 114, 114);">
                    <div class="state">
                        <h3>订单状态：</h3>
                        <p>{{$dat['info']['enabled']}}</p>
                        <span>订单更新时间： {{$dat['info']['registerTime']}}</span>
                    </div>
                    <p>客户姓名：{{$dat['info']['role']}}</p>
                    <p>客户地址：{{$dat['info']['address']}}</p>
                    <p>联系电话：{{$dat['info']['lastLoginTime']}}</p>
                </div>
                <div style="padding: 10px 20px; color: rgb(114, 114, 114);">
                    <h3>订单明细</h3>
                    <el-table :data="tableData" border show-summary :summary-method="getSummaries" style="width: 100%; margin-top: 20px">
                        <el-table-column prop="name" label="商品名称">
                        </el-table-column>
                        <el-table-column prop="amount1" label="数量" width="200">
                        </el-table-column>
                        <el-table-column prop="amount2" label="单价" width="200">
                        </el-table-column>
                        <el-table-column prop="amount3" label="小计" width="200">
                        </el-table-column>
                    </el-table>
                    </template>
                </div>
                <div style="padding: 10px 20px; color: rgb(114, 114, 114);">
                    <h3>订单历史</h3>
                    <el-table :data="orderHistory" border style="width: 100%; margin-top: 20px">
                        <el-table-column prop="detailed" label="操作明细" width="200">
                        </el-table-column>
                        <el-table-column prop="time" label="操作时间">
                        </el-table-column>
                    </el-table>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('asset/admin/jquery3-4-1/jquery.min.js')}}"></script>
    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    collapse: false,
                    tableData: [
                        @foreach($dat['det'] as $key => $det) {
                            name: '{{$det["name"]}}', //商品名称
                            amount1: '{{$det["num"]}}', //数量
                            amount2: '{{$det["amount"]}}', //单价
                            amount3: '{{$det["amount"]}}', //小计
                        },
                        @endforeach
                    ],
                    orderHistory: [
                        @foreach($dat['his'] as $key => $his) {
                            detailed: "{{$his['name']}}", //操作明细
                            time: '{{$his["date"]}}', //操作时间
                        },
                        @endforeach
                    ],
                    dialogFormVisible: false, //页面隐藏
                    form: {
                        name: '', //活动名称
                        region: '2', //活动区域
                    },
                    formLabelWidth: '100px' //物流配送名称宽度
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
                //合计
                getSummaries(param) {
                    const {
                        columns,
                        data
                    } = param;
                    let values = [];
                    const sums = [];
                    columns.forEach((column, index) => {
                        if (index === 0) {
                            sums[index] = '总价';
                            return;
                        }
                        const values = data.map(item => Number(item[column.property]));
                        if (column.property === 'amount3') {
                            sums[index] = values.reduce((prev, curr) => {
                                const value = Number(curr);
                                if (!isNaN(value)) {
                                    return prev + curr;
                                } else {
                                    return prev;
                                }
                            }, 0);
                            sums[index] += ' 元';
                        } else {
                            sums[index] = '';
                        }
                    });

                    return sums;
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
    <!-- 菜单 -->
    <script src="{{asset('asset/admin/menu.js')}}"></script>
</body>

</html>