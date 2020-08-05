layui.define(function (exports) {
    var MOD = 'iconExtend';
    var iconExtend = {
        iconfont: "../../../css/layui/icon-extend/iconfont.css",
        loader: function (name) {
            layui.link(this[name]);
        },
    };
    exports(MOD, iconExtend);
});
