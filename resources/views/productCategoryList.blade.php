<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品分类管理</title>
    <link rel="stylesheet" href="{{asset('asset/admin/element-ui/lib/theme-chalk/index.css')}}">
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
    z-index: 3;
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

.main>div {
    border-radius: 4px;
    margin-bottom: 20px;
    background: #fff;
    padding: 20px;
    border: 1px solid #e4e5e6;
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
        z-index: 2;
    }

    .menu-active {
        left: -100%;
    }

    ul.el-menu-vertical-demo.el-menu {
        border: none;
    }

    .el-menu-vertical-demo:not(.el-menu--collapse) {
        width: 100%;
    }

    .el-pagination__total {
        margin-bottom: 10px;
    }

    .el-message-box {
        width: calc(100% - 40px);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%)
    }

    .control-button {
        float: left !important;
    }

    .miao-shu {
        margin-top: 8px;
    }

    .back-button {
        margin-top: 10px;
    }
}

.control label {
    display: block;
    color: #666;
    margin-bottom: 6px;
}

.control-button {
    width: auto;
    float: right;
}

.control-button button {
    margin-top: 20px;
}

.data-table .pagination {
    margin-top: 20px;
}

.data-table.el-col.el-col-24.el-col-md-24 {
    margin-bottom: 0;
}

.add-page .add-page-button {
    margin-bottom: 2px;
}



.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.avatar-uploader .el-upload:hover {
    border-color: #409EFF;
}

.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
}

.avatar {
    width: 178px;
    height: 178px;
    display: block;
}

.el-form-item__content {
    line-height: 0;
}
</style>

<body>
    @include('nav')
    <div id="app">
        <!-- 主体内容 -->
        <div class="main">
            <el-col :md="24" class="control" v-if="!adding">
                <el-row :gutter="20">
                    <el-col :md="12" class="control-category">
                        <label>商品类别名</label>
                        <el-input placeholder="商品类别名" v-model="category" @input="Search" clearable></el-input>
                    </el-col>
                    <el-col :md="12" class="control-description">
                        <label class="miao-shu">描述</label>
                        <el-input placeholder="描述" v-model="description" @input="Search" clearable></el-input>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col class="control-button">
                        <el-button type="success" @click="Search">查询</el-button>
                        <el-button type="success" @click="Reset">重置</el-button>
                        <el-button type="success" @click="OpenAddPage">添加产品分类</el-button>
                    </el-col>
                </el-row>
            </el-col>

            <el-col :md="24" class="data-table" v-if="!adding">
                <template>
                    <el-table border :data="tableData.slice((currpage - 1) * pagesize, currpage * pagesize)" stripe
                        style="width: 100%" @selection-change="changeFun($event)">
                        <el-table-column type="selection" width="50" ref="multipleTable"></el-table-column>
                        <el-table-column prop="id" label="id" width="50"></el-table-column>
                        <el-table-column prop="category" label="商品类别名" width="120"></el-table-column>
                        <el-table-column prop="description" label="描述"></el-table-column>
                        <el-table-column prop="date" label="更新日期" width="120"></el-table-column>
                        <el-table-column prop="user" label="更新者" width="100"></el-table-column>
                        <el-table-column prop="content" label="操作" width="148">
                            <template slot-scope="scope">
                                <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                                <el-button size="mini" type="danger"
                                    @click="handleDelete(scope.$index, scope.row,$event)">删除
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>

                    <el-row>
                        <div class="pagination">
                            <el-pagination @current-change="handleCurrentChange" @size-change="handleSizeChange"
                                :page-sizes="[10, 20, 30, 40]" :page-size="pagesize"
                                layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
                            </el-pagination>
                        </div>
                    </el-row>
                </template>
            </el-col>

            <el-row class="add-page" v-if="adding">
                <el-form :model="addForm" status-icon :rules="rules" ref="addForm" label-width="100px"
                    enctype="multipart/form-data">
                    <el-form-item label="商品类名" prop="category">
                        <el-input placeholder="商品类名" v-model="addForm.category" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="商品描述" prop="description">
                        <el-input placeholder="商品描述" v-model="addForm.description" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="图片" prop="img">
                        <el-upload name="source" class="avatar-uploader" action="product/icon" :show-file-list="false"
                            :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
                            <img v-if="imageUrl" :src="imageUrl" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="排序" prop="order">
                        <el-input v-model.number="addForm.order"></el-input>
                    </el-form-item>
                    <el-form-item label="最后更新">
                        <el-input v-model="addForm.date" :disabled="true"></el-input>
                    </el-form-item>
                    <el-form-item label="更新者">
                        <el-input v-model="addForm.user" :disabled="true"></el-input>
                    </el-form-item>
                    <el-form-item class="add-page-button">
                        <el-button @click="resetForm('addForm')">重置</el-button>
                        <el-button type="success" @click="submitForm('addForm')">保存</el-button>
                        <el-button class="back-button" type="success" @click="Back">返回</el-button>
                    </el-form-item>
                </el-form>
            </el-row>
        </div>
    </div>




    <script src="{{asset('asset/admin/vue/vue.js')}}"></script>
    <script src="{{asset('asset/admin/element-ui/lib/index.js')}}"></script>
    <script src="{{asset('asset/admin/jquery3-4-1/jquery.min.js')}}"></script>
    <script>
    const fenlei = new Vue({
        el: '#app',
        data() {
            var Order = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('排序不能为空'));
                }
                setTimeout(() => {
                    if (!Number.isInteger(value)) {
                        callback(new Error('请输入数字值'));
                    } else {
                        callback();
                    }
                }, 500);
            };
            var Category = (rule, value, callback) => { //验证添加的类名
                if (value === '') {
                    callback(new Error('商品类名不能为空！'));
                } else {
                    callback();
                }
            };
            var Description = (rule, value, callback) => { //验证添加的描述
                if (value === '') {
                    callback(new Error('商品描述不能为空！'));
                } else {
                    callback();
                }
            };
            return {
                collapse: false, //主菜单展开
                category: '', //商品类别名
                description: '', //描述
                openeds: ['1'], //默认展开折叠的二级菜单
                tableData: [],
                multipleSelection: [], //返回的是选中的列的数组集合
                dataMap: '',
                checksId: [],
                data: [],

                pagesize: 10,
                currpage: 1,
                search: '',
                adding: false, //正在添加或编辑产品分类
                isEdit: false, //编辑
                addForm: {
                    category: '', //正在添加的分类名
                    description: '', //正在添加的描述
                    user: '', //更新者
                    order: '', //排序
                    imgUrl: '' //图片地址
                },
                imageUrl: '',
                rules: {
                    category: [{
                        validator: Category,
                        trigger: 'blur'
                    }],
                    description: [{
                        validator: Description,
                        trigger: 'blur'
                    }],
                    order: [{
                        validator: Order,
                        trigger: 'blur'
                    }]
                },
            };
        },
        created() {
            // axios('./data/fenlei.json')
            //     .then(res => {
            //         // this.data = res.data.data;
            //         // console.log(this.data);
            //         var resData = res.data.data;

            //         let map = new Map();
            //         for (var i = 0; i < resData.length; i++) {
            //             var key = resData[i].id;
            //             var vaule = resData[i];
            //             map.set(key, vaule);
            //         }

            //         this.dataMap = map;

            //         this.ForEachMap();
            //     }).catch(err => {
            //         console.log(err);
            //     })

            this.queryInfo();
        },
        methods: {
            queryInfo() {
                var resData = [
                    @foreach($us as $key => $dat) {
                        "id": "{{$dat['id']}}",
                        "category": "{{$dat['category']}}",
                        "description": "{{$dat['description']}}",
                        "date": "{{$dat['date']}}",
                        "user": "{{$dat['user']}}",
                        "image": "{{$dat['image']}}",
                        "sort": "{{$dat['sort']}}"
                    },
                    @endforeach
                ];

                let map = new Map();
                for (var i = 0; i < resData.length; i++) {
                    var key = resData[i].id;
                    var vaule = resData[i];
                    map.set(key, vaule);
                }

                this.dataMap = map;

                this.ForEachMap();
            },
            handleOpen(key, keyPath) {
                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
            },
            //遍历map存入data
            ForEachMap() {
                console.log(this.dataMap);
                var data = [];
                for (val of this.dataMap.values()) { //遍历map
                    data.push(val);
                }

                this.data = data;
                this.tableData = this.data;
            },
            //文本提示
            Tmessage(info) {
                this.$message({
                    showClose: true,
                    message: info,
                });
            },
            //成功提示
            Smessage(info) {
                this.$message({
                    showClose: true,
                    message: info,
                    type: 'success'
                });
            },
            // 侧边栏折叠
            collapseChage() {
                // this.collapse = !this.collapse;
            },
            //重置
            Reset() {
                this.category = '';
                this.description = '';
                this.tableData = this.data;
            },
            //搜索
            Search() {
                if (this.category != '' || this.description != '') {
                    var resData = [];
                    if (this.category == '') {
                        for (var j = 0; j < this.data.length; j++) {
                            if (this.data[j].description.indexOf(this.description) != -1) {
                                resData.push(this.data[j]);
                            }
                        };
                    } else if (this.description == '') {
                        for (var i = 0; i < this.data.length; i++) {
                            if (this.data[i].category.indexOf(this.category) != -1) {
                                resData.push(this.data[i]);
                            }
                        };
                    } else {
                        for (var i = 0; i < this.data.length; i++) {
                            if (this.data[i].category.indexOf(this.category) != -1 && this.data[i].description
                                .indexOf(this.description) != -1) {
                                resData.push(this.data[i]);
                            }
                        };
                    }
                    this.tableData = resData;
                } else {
                    this.tableData = this.data;
                }
            },
            //勾选行
            changeFun(val, event) {
                //获取用户的选中
                this.multipleSelection = [];
                this.checksId = [];
                for (var i = 0; i < val.length; i++) {
                    this.checksId.push(val[i].id);
                }
                console.log(this.checksId);
            },
            //编辑表格行
            handleEdit(index, row) {
                console.log(index, row);
                this.adding = true;

                this.addForm.category = row.category;
                this.addForm.description = row.description;
                this.addForm.id = row.id;
                this.addForm.date = row.date;
                this.addForm.user = row.user;
                var sort = parseInt(row.sort);
                this.addForm.order = sort;
                this.addForm.imgUrl = row.image;
                this.imageUrl = row.image;

                this.isEdit = true;
            },
            //删除请求
            DeleteData(delId) {
                console.log(delId);
                $.ajax({
                    type: "post",
                    url: "product/del",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: delId,
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.code == 200) {
                            fenlei.$message({
                                message: data.msg,
                                type: 'success',
                                duration: 1000,
                                onClose: function() {
                                    //页面刷新
                                    window.location.reload();
                                }
                            });
                        } else {
                            fenlei.$message({
                                message: data.msg,
                                type: 'warning',
                                duration: 1000
                            });
                        }
                    },
                    error: function(XMLResponse) {
                        fenlei.$message.error({
                            message: '服务器连接失败',
                            duration: 2000
                        });
                    }
                });
            },
            //删除表格行
            handleDelete(index, row, event) {
                // console.log(index, row);

                /*判断点击删除的行的是否已选中*/
                var clickInArr = false;
                for (var k = 0; k < this.checksId.length; k++) {
                    if (this.checksId[k] == row.id) {
                        clickInArr = true;
                        break;
                    };
                }

                /* 是否删除提示弹窗 */
                this.$confirm('此操作将删除该分类, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    if (this.checksId.length == 0 || clickInArr == false) { //未多选 或点击的不在已勾选的行里，则只删除一条
                        var delId = [row.id];
                        // console.log(delId);

                        this.DeleteData(delId);

                        // this.dataMap.delete(row.id);
                        // this.ForEachMap();
                    } else { //多选删除
                        this.DeleteData(this.checksId);

                        // for (var i = 0; i < this.checksId.length; i++) {
                        //     this.dataMap.delete(this.checksId[i]);
                        // }
                        // this.ForEachMap();
                    };
                }).catch(() => {
                    var info = '已取消删除。'
                    this.Tmessage(info);
                });
            },
            //分页
            handleCurrentChange(cpage) {
                this.currpage = cpage;
            },
            //分页
            handleSizeChange(psize) {
                this.pagesize = psize;
            },
            //打开添加、编辑分类 页面
            OpenAddPage() {
                this.adding = true;
            },
            //关闭添加、编辑分类 页面
            ClosAddPage() {
                this.adding = false;
            },
            //返回
            Back() {
                if (this.addForm.category != "" || this.addForm.description != "" || this.addForm.order != "" ||
                    this.addForm.imgUrl != "") {
                    this.$confirm('数据未保存, 是否返回?', '提示', {
                        confirmButtonText: '返回',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this.addForm = {};
                        this.imageUrl = "";
                        this.ClosAddPage();
                    }).catch(() => {});
                } else {
                    this.ClosAddPage();
                }
            },
            //保存
            submitForm(formName) {
                console.log(this.addForm.order);
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if (this.isEdit == false) { //新添加
                            $.ajax({
                                type: "post",
                                url: "product/add",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    name: fenlei.addForm.category, //类名
                                    desc: fenlei.addForm.description, //描述
                                    sort: fenlei.addForm.order, //排序
                                    icon: fenlei.addForm.imgUrl, //图片
                                },
                                success: function(data) {
                                    // console.log(data);
                                    if (data.code == 200) {
                                        fenlei.$message({
                                            message: data.msg,
                                            type: 'success',
                                            duration: 1000,
                                            onClose: function() {
                                                //页面刷新
                                                window.location.reload();
                                            }
                                        });
                                    } else {
                                        fenlei.$message({
                                            message: data.msg,
                                            type: 'warning',
                                            duration: 1000
                                        });
                                    }
                                },
                                error: function(XMLResponse) {
                                    fenlei.$message.error({
                                        message: '服务器连接失败',
                                        duration: 2000
                                    });
                                }
                            });
                        } else { //编辑保存
                            $.ajax({
                                type: "post",
                                url: "product/mod",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    id: fenlei.addForm.id, //id
                                    name: fenlei.addForm.category, //类名
                                    desc: fenlei.addForm.description, //描述
                                    sort: fenlei.addForm.order, //排序
                                    icon: fenlei.addForm.imgUrl, //图片
                                },
                                success: function(data) {
                                    console.log(data);
                                    if (data.code == 200) {
                                        fenlei.$message({
                                            message: data.msg,
                                            type: 'success',
                                            duration: 1000,
                                            onClose: function() {
                                                //页面刷新
                                                window.location.reload();
                                            }
                                        });
                                    } else {
                                        fenlei.$message({
                                            message: data.msg,
                                            type: 'warning',
                                            duration: 1000
                                        });
                                    }
                                },
                                error: function(XMLResponse) {
                                    console.log(XMLResponse);
                                    fenlei.$message.error({
                                        message: '服务器连接失败',
                                        duration: 2000
                                    });
                                }
                            });
                        }
                    } else {
                        fenlei.$message.error({
                            message: '填写有误',
                            duration: 2000
                        });
                        return false;
                    }
                });
            },
            resetForm(formName) {
                // this.$refs[formName].resetFields();
                this.addForm = {
                    category: "",
                    date: "",
                    description: "",
                    id: "",
                    img: "",
                    imgUrl: "",
                    order: "",
                };
                this.imageUrl = "";
            },
            handleAvatarSuccess(res, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
                this.addForm.imgUrl = res;

                // console.log(res);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            }
        }
    })


    /*jq*/
    $(document).ready(function() {
        var mediaWidth = $(window).width(); //屏幕视口宽度
        var mediaHeight = $(window).height(); //屏幕视口宽度
        // console.log(mediaWidth);
        // console.log(mediaHeight);

        // var tableHeight = mediaHeight - 385;  //改表格高度适应100vh
        // $('.data-table.el-col.el-col-24.el-col-md-24>div:nth-child(1)').css('height', tableHeight + 'px');

        if (mediaWidth < 768) {
            $('.el-pagination__sizes').after("<br>"); //表格底部控件 追加换行
        }

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

        /*折叠菜单*/
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