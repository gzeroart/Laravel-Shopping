<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>添加option</title>
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
</style>

<body>
    @include('nav')
    <div id="app">
        <!-- 主体内容 -->
        <div class="main">
            <div class="main-form">
                <el-form :model="dynamicValidateForm" ref="dynamicValidateForm" label-width="150px" class="demo-dynamic">
                    <el-form-item prop="name" label="option名称" :rules="{required: true, message: '名称不能为空', trigger: 'blur' }">
                        <el-input v-model="dynamicValidateForm.name"></el-input>
                    </el-form-item>
                    <el-form-item v-for="(domain, index) in dynamicValidateForm.domains" :label="'Option Value' + index" :key="domain.key" :prop="'domains.' + index + '.value'" :rules="{
                                required: true, message: '请输入参数，无需则留空', trigger: 'blur'
                              }">
                        <el-col :span="11" style="margin-right: 20px;">
                            <el-input v-model="domain.value"></el-input>
                        </el-col>
                        <el-button @click.prevent="removeDomain(domain)">删除</el-button>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="addDomain">添加</el-button>
                        <el-button type="primary" @click="submitForm('dynamicValidateForm')">提交</el-button>
                        <el-button onclick="window.history.go(-1)">返回</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
    <script src="{{asset('asset/admin/jquery3-4-1/jquery.min.js')}}"></script>
    <script>
        const addop = new Vue({
            el: '#app',
            data() {
                return {
                    collapse: false,
                    dynamicValidateForm: {
                        domains: [],
                        name: ''
                    }
                }
            },
            methods: {
                submitForm(_this) {
                    // console.log(this.dynamicValidateForm);
                    // console.log(this.dynamicValidateForm.domains);
                    // console.log(addop.dynamicValidateForm.domains.length);
                    var v_value = '';
                    if (addop.dynamicValidateForm.domains.length > 0) {
                        v_value = addop.dynamicValidateForm.domains;
                        console.log(v_value);
                    }
                    if (addop.dynamicValidateForm.name != '') {
                        $.ajax({
                            type: "post",
                            url: "option/add",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                name: addop.dynamicValidateForm.name,
                                val: v_value
                            },
                            success: function(data) {
                                if (data.code == 200) {
                                    addop.$message({
                                        message: data.msg,
                                        type: 'success',
                                        duration: 1500,
                                        onClose: function() {
                                            window.location = './option';
                                        }
                                    });
                                } else {
                                    addop.$message({
                                        message: data.msg,
                                        type: 'warning',
                                        duration: 3000
                                    });
                                }
                            },
                            error: function(XMLResponse) {
                                addop.$message.error({
                                    message: '服务器连接失败',
                                    duration: 3000
                                });
                            }
                        });
                    }
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

                //删除
                removeDomain(item) {
                    var index = this.dynamicValidateForm.domains.indexOf(item)
                    console.log(index);

                    if (index >= 0) {
                        this.dynamicValidateForm.domains.splice(index, 1)
                    }
                },
                //新增内容
                addDomain() {
                    this.dynamicValidateForm.domains.push({
                        value: '',
                        key: Date.now()
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
    <!-- 菜单 -->
    <script src="{{asset('asset/admin/menu.js')}}"></script>
</body>

</html>