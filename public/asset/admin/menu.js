const menu = new Vue({
    el: '#navapp',
    data() {
        return {
            collapse: false,
        }
    }, methods: {
        //页面跳转
        navJump(_page) {
            //console.log(document.location.protocol);
            //地址跳转
            window.location = document.location.protocol + '//' + document.domain + '/admin/' + _page;
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
    }
});