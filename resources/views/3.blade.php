<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
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
        z-index: 1111111;
    }
    
    .header h3 {
        display: inline;
    }
    
    .collapse-btn {
        padding: 0 21px;
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
    }
    
    .main {
        padding-top: 71px;
        display: block;
    }
    
    @media screen and (max-width: 450px) {
        .el-menu--collapse {
            left: -80px;
        }
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
        <div class="menu">
            <el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b" :collapse="collapse">
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
            {{$name}}
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    collapse: false
                };
            },
            methods: {
                handleOpen(key, keyPath) {
                    console.log(key, keyPath);
                },
                handleClose(key, keyPath) {
                    console.log(key, keyPath);
                },
                // 侧边栏折叠
                collapseChage() {
                    this.collapse = !this.collapse;
                },
            }
        })
    </script>
</body>

</html>