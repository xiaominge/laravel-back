layui.define(['jquery'], function (exports) {
    var $ = layui.jquery;

    var TreeList = function () {
        this.MOD = 'treeList';
    };

    TreeList.prototype.render = function (opts) {
        if (typeof opts != "object") opts = {};
        // 初始化状态
        var initAction = opts.initAction || 'expand';
        // tbody 样式类名
        var tbodyClass = opts.tbodyClass || 'tree-list';
        // item ID
        var itemId = opts.itemId || 'item-id';
        // parent ID
        var parentId = opts.parentId || 'parent-id';
        // 属性选择器
        var attrSelector = opts.attrSelector || 'data-action';
        // Font Family
        var familyClass = opts.familyClass || 'layui-icon';
        // 收起图标的样式
        var collapseClass = opts.collapseClass || 'layui-icon-triangle-r';
        // 展开图标的样式
        var expandClass = opts.expandClass || 'layui-icon-triangle-d';
        // 表格行选择器
        var trSelector = 'tbody.' + tbodyClass + ' tr';
        // tr 元素中的选择器
        var itemSelector = 'i[' + attrSelector + ']';
        // 非一级元素
        var notTopLevelItem = $(trSelector + "[" + parentId + "!='0']");
        // 字体图标的样式
        var iconClass = {
            'collapse': collapseClass,
            'expand': expandClass,
        };

        var common = {
            getItemId: function (o) {
                return o.parents('tr').attr(itemId);
            },

            getChildByItemId: function (itemId) {
                return $(trSelector + "[" + parentId + "='" + itemId + "']");
            },

            expand: function (o) {
                o.removeClass(collapseClass);
                o.addClass(expandClass);
                o.attr(attrSelector, 'collapse');
            },

            collapse: function (o) {
                o.removeClass(expandClass);
                o.addClass(collapseClass);
                o.attr(attrSelector, 'expand');
            },

            collapseAll: function (o) {
                notTopLevelItem.hide();
                common.collapse($(trSelector).find(itemSelector));
            },

            expandAll: function (o) {
                $(trSelector).show();
                common.expand($(trSelector).find(itemSelector));
            },

            collapseItem: function (o) {
                common.collapse(o);
                var itemId = common.getItemId(o);
                common.getChildByItemId(itemId).each(function (i, el) {
                    $(el).hide();
                    common.collapseItem($(el).find(itemSelector));
                });
            },

            expandItem: function (o) {
                common.expand(o);
                var itemId = common.getItemId(o);
                common.getChildByItemId(itemId).show();
            },

            action: function (o) {
                var o = $(o);
                var action = o.attr('data-action');
                if (action == 'collapse') {
                    o.is('i') ? this.collapseItem(o) : this.collapseAll(o);
                } else if (action == 'expand') {
                    o.is('i') ? this.expandItem(o) : this.expandAll(o);
                }
            },
        };

        var execute = {
            init: function () {
                var attrVal = initAction == 'expand' ? 'collapse' : 'expand';
                $(itemSelector).attr(attrSelector, attrVal);
                $(itemSelector).addClass(familyClass);
                $(itemSelector).addClass(iconClass[initAction]);
            },

            clickListen: function () {
                $("body").on('click', '[data-action]', function (e) {
                    var el = e.currentTarget;
                    common.action(el);
                });
            },
        };

        execute.init();
        execute.clickListen();
    };

    var treeList = new TreeList();
    exports(treeList.MOD, treeList);
});
