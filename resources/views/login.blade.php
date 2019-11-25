<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- import CSS -->
    <link rel="stylesheet" href="{{asset('asset/admin/element-ui/lib/theme-chalk/index.css')}}">
    <script src="{{asset('asset/admin/vue/vue.js')}}"></script>
    <script src="{{asset('asset/admin/element-ui/lib/index.js')}}"></script>
    <style>
        body {
            background-color: #2d3a4b;
        }

        #login-box {
            top: 100px;
            width: 400px;
            max-width: 100%;
            margin: auto;
            /*background: #000;*/
        }

        #login-box input {
            /* background: transparent !important; */
        }

        #login-box h1 {
            font-size: 2.5em;
            color: #fff;
            text-align: center;
        }

        #login-box label {
            font-size: 18px;
            color: #ccc;
            padding: 0px;
        }

        #login-box button {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div id="app">
        <el-row id="login-box" @keyup.enter.native="onSubmit">
            <el-col>
                <div class="grid-content bg-purple">
                    <h1 v-text="logintitle"></h1>
                    <el-form :label-position="labelPosition" id="myform" label-width="80px" :model="formLabelAlign">
                        <!-- _token -->
                        {!! csrf_field() !!}
                        <el-form-item label="账号">
                            <el-input v-model="formLabelAlign.name" name="name"></el-input>
                        </el-form-item>
                        <el-form-item label="密码">
                            <el-input v-model="formLabelAlign.passwd" type="password" name="passwd"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button v-text="loginbutton" type="primary" @click="onSubmit"> </el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </el-col>
        </el-row>

    </div>
</body>
<script src="{{asset('asset/admin/jquery3-4-1/jquery.min.js')}}"></script>

<script>
    const login = new Vue({
        el: '#app',
        data: function() {
            return {
                logintitle: '后台登录',
                labelPosition: 'top',
                loginbutton: '立即登录',
                formLabelAlign: {
                    name: '',
                    passwd: ''
                }
            }
        },
        methods: {
            onSubmit() {
                // console.log(this.formLabelAlign.name + '===' + this.formLabelAlign.passwd);
                //判断表单是否为空
                if (this.formLabelAlign.name == '' || this.formLabelAlign.passwd == '') {
                    //this.$message.error('请输入完整账号密码');
                    let _this = this;
                    login.$message.error({
                        message: '请输入完整账号密码',
                        duration: 1500
                    });
                } else {
                    //实例化表单
                    var fm = new FormData($('#myform')[0]);
                    $.ajax({
                        type: "post",
                        url: "logchect",
                        dataType: "json",
                        data: fm,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            // console.log(data.msg);
                            if (data.code == 200) {
                                login.$message({
                                    message: data.msg,
                                    type: 'success',
                                    duration: 1500,
                                    onClose: function() {
                                        window.location = './productList';
                                    }
                                });
                            } else {
                                login.$message({
                                    message: data.msg,
                                    type: 'warning',
                                    duration: 1500
                                });
                            }
                        },
                        error: function(XMLResponse) {
                            login.$message.error({
                                message: '服务器连接失败',
                                duration: 1500
                            });
                        }
                    });
                }
            }
        },
        computed: {}


    });
</script>

</html>