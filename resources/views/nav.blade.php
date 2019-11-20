<div id="navapp">
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
        <el-menu default-active="{{$pageOn}}" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b" :collapse="collapse" unique-opened>
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
            <!-- style="color: rgb(255, 208, 75);" -->
            <el-menu-item index="user" @click="navJump('user')">
                <!-- disabled -->
                <i class="el-icon-user"></i>
                <span slot="title">用户管理</span>
            </el-menu-item>
            <el-menu-item index="comment" @click="navJump('comment')">
                <i class="el-icon-s-comment"></i>
                <span slot="title">评论管理</span>
            </el-menu-item>
            <el-submenu index="5">
                <template slot="title"> <i class="el-icon-document-copy"></i><span>文章管理</span></template>
                <el-menu-item-group>
                    <el-menu-item index="articlesort" @click="navJump('articlesort')">分类管理</el-menu-item>
                    <el-menu-item index="5-2">文章管理</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
            <el-menu-item index="6" :default-active="aaaa">
                <i class="el-icon-collection"></i>
                <span slot="title">电子钱包初始金额设定</span>
            </el-menu-item>
        </el-menu>
    </div>
</div>