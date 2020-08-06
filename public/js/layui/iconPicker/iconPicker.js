/**
 * Layui图标选择器
 * @author wujiawei0926@yeah.net
 * @version 1.1
 */

layui.define(['laypage', 'form'], function (exports) {
    "use strict";

    var IconPicker = function () {
            this.v = '1.1';
        }, _MOD = 'iconPicker',
        _this = this,
        $ = layui.jquery,
        laypage = layui.laypage,
        form = layui.form,
        BODY = 'body',
        TIPS = '请选择图标';

    /**
     * 渲染组件
     */
    IconPicker.prototype.render = function (options) {
        var opts = options,
            // DOM选择器
            elem = opts.elem,
            // 数据类型：fontClass/unicode
            type = opts.type || 'fontClass',
            // 是否分页：true/false
            page = opts.page == undefined ? true : opts.page,
            // 每页显示数量
            limit = opts.limit || 12,
            // 是否开启搜索：true/false
            search = opts.search == undefined ? true : opts.search,
            // 每个图标格子的宽度
            cellWidth = opts.cellWidth || null,
            bodyWidth = opts.bodyWidth || '276px',
            bodyHeight = opts.bodyHeight || '258px',
            size = opts.size || 'medium',
            // 点击回调
            click = opts.click,
            // 渲染成功后的回调
            success = opts.success,
            // json数据
            data = {},
            // 唯一标识
            tmp = new Date().getTime(),
            // 是否使用的class数据
            isFontClass = (type === 'fontClass'),
            // 初始化时input的值
            ORIGINAL_ELEM_VALUE = $(elem).val(),
            TITLE = 'layui-select-title',
            TITLE_ID = 'layui-select-title-' + tmp,
            ICON_BODY = 'layui-iconpicker-' + tmp,
            PICKER_BODY = 'layui-iconpicker-body-' + tmp,
            PAGE_ID = 'layui-iconpicker-page-' + tmp,
            LIST_BOX = 'layui-iconpicker-list-box',
            selected = 'layui-form-selected',
            unselect = 'layui-unselect';

        var a = {
            init: function () {
                data = common.getData[type]();

                a.hideElem().createSelect().createBody().toggleSelect();
                a.preventEvent().inputListen();
                common.loadCss();

                if (success) {
                    success(this.successHandle());
                }

                return a;
            },
            successHandle: function () {
                var d = {
                    options: opts,
                    data: data,
                    id: tmp,
                    elem: $('#' + ICON_BODY)
                };
                return d;
            },
            /**
             * 隐藏elem 、修改为不隐藏
             */
            hideElem: function () {
                $(elem).css({
                    position: 'absolute',
                    left: '55px',
                    width: '150px',
                    'z-index': '1',
                });
                if (size != 'small') {
                    $(elem).css({
                        height: '40px',
                        'border-radius': '4px',
                    });
                }
                return a;
            },
            /**
             * 绘制select下拉选择框
             */
            createSelect: function () {
                var oriIcon = '<i class="layui-icon">';

                // 默认图标
                if (ORIGINAL_ELEM_VALUE === '') {
                    if (isFontClass) {
                        ORIGINAL_ELEM_VALUE = 'layui-icon-circle-dot';
                    } else {
                        ORIGINAL_ELEM_VALUE = '&#xe617;';
                    }
                }

                if (isFontClass) {
                    oriIcon = '<i class="layui-icon ' + ORIGINAL_ELEM_VALUE + '">';
                } else {
                    oriIcon += ORIGINAL_ELEM_VALUE;
                }
                oriIcon += '</i>';

                var selectHtml = '<div class="layui-iconpicker layui-unselect layui-form-select" id="' + ICON_BODY + '">' +
                    '<div class="' + TITLE + '" id="' + TITLE_ID + '">' +
                    '<div class="layui-iconpicker-item layui-iconpicker-item-' + size + '">' +
                    '<span class="layui-iconpicker-icon layui-unselect layui-iconpicker-icon-' + size + '">' +
                    oriIcon +
                    '</span>' +
                    '<i class="layui-edge" style="display: none"></i>' +
                    '</div>' +
                    '</div>' +
                    '<div class="layui-anim layui-anim-upbit layui-anim-' + size + '" style="">' +
                    '123' +
                    '</div>';
                $(elem).after(selectHtml);
                return a;
            },
            /**
             * 展开/折叠下拉框
             */
            toggleSelect: function () {
                var item = '#' + TITLE_ID + ' .layui-iconpicker-item,#' + TITLE_ID + ' .layui-iconpicker-item .layui-edge,' + elem;
                a.event('click', item, function (e) {
                    var $icon = $('#' + ICON_BODY);
                    if ($icon.hasClass(selected)) {
                        $icon.removeClass(selected).addClass(unselect);
                    } else {
                        // 隐藏其他picker
                        $('.layui-form-select').removeClass(selected);
                        // 显示当前picker
                        $icon.addClass(selected).removeClass(unselect);
                    }
                    e.stopPropagation();
                });
                return a;
            },
            /**
             * 绘制主体部分
             */
            createBody: function () {
                // 获取数据
                var searchHtml = '';

                if (search) {
                    searchHtml = '<div class="layui-iconpicker-search">' +
                        '<input class="layui-input">' +
                        '<i class="layui-icon">&#xe615;</i>' +
                        '</div>';
                }

                // 组合dom
                var bodyHtml = '<div class="layui-iconpicker-body" id="' + PICKER_BODY + '">' +
                    searchHtml +
                    '<div class="' + LIST_BOX + '"></div> ' +
                    '</div>';
                $('#' + ICON_BODY).find('.layui-anim').eq(0).html(bodyHtml);
                a.search().createList().check().page();

                return a;
            },
            /**
             * 绘制图标列表
             * @param text 模糊查询关键字
             * @returns {string}
             */
            createList: function (text) {
                var d = data,
                    l = d.length,
                    pageHtml = '',
                    listHtml = $('<div class="layui-iconpicker-list">')//'<div class="layui-iconpicker-list">';

                // 计算分页数据
                var _limit = limit, // 每页显示数量
                    _pages = l % _limit === 0 ? l / _limit : parseInt(l / _limit + 1), // 总计多少页
                    _id = PAGE_ID;

                // 图标列表
                var icons = [];

                for (var i = 0; i < l; i++) {
                    var obj = d[i];

                    // 判断是否模糊查询
                    if (text && obj.indexOf(text) === -1) {
                        continue;
                    }

                    // 是否自定义格子宽度
                    var style = '';
                    if (cellWidth !== null) {
                        style += ' style="width:' + cellWidth + '"';
                    }

                    // 每个图标dom
                    var icon = '<div class="layui-iconpicker-icon-item" title="' + obj + '" ' + style + '>';
                    if (isFontClass) {
                        icon += '<i class="layui-icon ' + obj + '"></i>';
                    } else {
                        icon += '<i class="layui-icon">' + obj.replace('amp;', '') + '</i>';
                    }
                    icon += '</div>';

                    icons.push(icon);
                }

                // 查询出图标后再分页
                l = icons.length;
                _pages = l % _limit === 0 ? l / _limit : parseInt(l / _limit + 1);
                for (var i = 0; i < _pages; i++) {
                    // 按limit分块
                    var lm = $('<div class="layui-iconpicker-icon-limit" id="layui-iconpicker-icon-limit-' + tmp + (i + 1) + '">');

                    for (var j = i * _limit; j < (i + 1) * _limit && j < l; j++) {
                        lm.append(icons[j]);
                    }

                    listHtml.append(lm);
                }

                // 无数据
                if (l === 0) {
                    listHtml.append('<p class="layui-iconpicker-tips">无数据</p>');
                }

                // 判断是否分页
                if (page) {
                    $('#' + PICKER_BODY).addClass('layui-iconpicker-body-page');
                    pageHtml = '<div class="layui-iconpicker-page" id="' + PAGE_ID + '">' +
                        '<div class="layui-iconpicker-page-count">' +
                        '<span id="' + PAGE_ID + '-current">1</span>/' +
                        '<span id="' + PAGE_ID + '-pages">' + _pages + '</span>' +
                        ' (<span id="' + PAGE_ID + '-length">' + l + '</span>)' +
                        '</div>' +
                        '<div class="layui-iconpicker-page-operate">' +
                        '<i class="layui-icon" id="' + PAGE_ID + '-prev" data-index="0" prev>&#xe603;</i> ' +
                        '<i class="layui-icon" id="' + PAGE_ID + '-next" data-index="2" next>&#xe602;</i> ' +
                        '</div>' +
                        '</div>';
                }


                $('#' + ICON_BODY).find('.layui-anim').find('.' + LIST_BOX).html('').append(listHtml).append(pageHtml);
                return a;
            },
            // 阻止Layui的一些默认事件
            preventEvent: function () {
                var item = '#' + ICON_BODY + ' .layui-anim';
                a.event('click', item, function (e) {
                    e.stopPropagation();
                });
                return a;
            },
            // 分页
            page: function () {
                var icon = '#' + PAGE_ID + ' .layui-iconpicker-page-operate .layui-icon';

                $(icon).unbind('click');
                a.event('click', icon, function (e) {
                    var elem = e.currentTarget,
                        total = parseInt($('#' + PAGE_ID + '-pages').html()),
                        isPrev = $(elem).attr('prev') !== undefined,
                        // 按钮上标的页码
                        index = parseInt($(elem).attr('data-index')),
                        $cur = $('#' + PAGE_ID + '-current'),
                        // 点击时正在显示的页码
                        current = parseInt($cur.html());

                    // 分页数据
                    if (isPrev && current > 1) {
                        current = current - 1;
                        $(icon + '[prev]').attr('data-index', current);
                    } else if (!isPrev && current < total) {
                        current = current + 1;
                        $(icon + '[next]').attr('data-index', current);
                    }
                    $cur.html(current);

                    // 图标数据
                    $('#' + ICON_BODY + ' .layui-iconpicker-icon-limit').hide();
                    $('#layui-iconpicker-icon-limit-' + tmp + current).show();
                    e.stopPropagation();
                });
                return a;
            },
            /**
             * 搜索
             */
            search: function () {
                var item = '#' + PICKER_BODY + ' .layui-iconpicker-search .layui-input';
                a.event('input propertychange', item, function (e) {
                    var elem = e.target,
                        t = $(elem).val();
                    a.createList(t);
                });
                return a;
            },
            /**
             * 点击选中图标
             */
            check: function () {
                var item = '#' + PICKER_BODY + ' .layui-iconpicker-icon-item';
                a.event('click', item, function (e) {
                    var el = $(e.currentTarget).find('.layui-icon'),
                        icon = '';
                    if (isFontClass) {
                        var clsArr = el.attr('class').split(/[\s\n]/),
                            cls = clsArr[1],
                            icon = cls;
                        $('#' + TITLE_ID).find('.layui-iconpicker-item .layui-icon').html('').attr('class', clsArr.join(' '));
                    } else {
                        var cls = el.html(),
                            icon = cls;
                        $('#' + TITLE_ID).find('.layui-iconpicker-item .layui-icon').html(icon);
                    }

                    $('#' + ICON_BODY).removeClass(selected).addClass(unselect);
                    $(elem).val(icon).attr('value', icon);
                    // 回调
                    if (click) {
                        click({
                            icon: icon
                        });
                    }

                });
                return a;
            },
            // 监听原始input数值改变
            inputListen: function () {
                var el = $(elem);
                a.event('change', elem, function () {
                    var value = el.val();
                })
                // el.change(function(){

                // });
                return a;
            },
            event: function (evt, el, fn) {
                $(BODY).on(evt, el, fn);
            }
        };

        var common = {
            /**
             * 加载样式表
             */
            loadCss: function () {
                var css = '.layui-iconpicker {max-width: ' + bodyWidth + ';}.layui-iconpicker .layui-anim{display:none;position:absolute;left:0;top:44px;padding:5px 0;z-index:899;min-width:100%;border:1px solid #d2d2d2;max-height:' + bodyHeight + ';overflow-y:auto;background-color:#fff;border-radius:2px;box-shadow:0 2px 4px rgba(0,0,0,.12);box-sizing:border-box;}.layui-iconpicker .layui-anim-small{top: 33px;}.layui-iconpicker-item{border:1px solid #e6e6e6;width:50px;height:38px;border-radius:4px;cursor:pointer;position:relative;}.layui-iconpicker-item-small{height:28px;border-radius:2px;}.layui-iconpicker-icon{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;width:48px;height:100%;float:left;text-align:center;background:#fff;transition:all .3s;}.layui-iconpicker-icon i{line-height:38px;font-size:18px;}.layui-iconpicker-icon-small i{line-height:30px;}.layui-iconpicker-item:hover{border-color:#D2D2D2!important;}.layui-iconpicker-item:hover .layui-iconpicker-icon{border-color:#D2D2D2!important;}.layui-iconpicker.layui-form-selected .layui-anim{display:block;}.layui-iconpicker-body{padding:6px;}.layui-iconpicker .layui-iconpicker-list{background-color:#fff;border:1px solid #ccc;border-radius:4px;}.layui-iconpicker .layui-iconpicker-icon-item{display:inline-block;width:21.1%;line-height:36px;text-align:center;cursor:pointer;vertical-align:top;height:36px;margin:4px;border:1px solid #ddd;border-radius:2px;transition:300ms;}.layui-iconpicker .layui-iconpicker-icon-item i.layui-icon{font-size:17px;}.layui-iconpicker .layui-iconpicker-icon-item:hover{background-color:#eee;border-color:#ccc;-webkit-box-shadow:0 0 2px #aaa,0 0 2px #fff inset;-moz-box-shadow:0 0 2px #aaa,0 0 2px #fff inset;box-shadow:0 0 2px #aaa,0 0 2px #fff inset;text-shadow:0 0 1px #fff;}.layui-iconpicker-search{position:relative;margin:0 0 6px 0;border:1px solid #e6e6e6;border-radius:2px;transition:300ms;}.layui-iconpicker-search:hover{border-color:#D2D2D2!important;}.layui-iconpicker-search .layui-input{cursor:text;display:inline-block;width:86%;border:none;padding-right:0;margin-top:1px;}.layui-iconpicker-search .layui-icon{position:absolute;top:11px;right:4%;}.layui-iconpicker-tips{text-align:center;padding:8px 0;cursor:not-allowed;}.layui-iconpicker-page{margin-top:6px;margin-bottom:-6px;font-size:12px;padding:0 2px;}.layui-iconpicker-page-count{display:inline-block;}.layui-iconpicker-page-operate{display:inline-block;float:right;cursor:default;}.layui-iconpicker-page-operate .layui-icon{font-size:12px;cursor:pointer;}.layui-iconpicker-body-page .layui-iconpicker-icon-limit{display:none;}.layui-iconpicker-body-page .layui-iconpicker-icon-limit:first-child{display:block;}';
                var $style = $('head').find('style[iconpicker]');
                if ($style.length === 0) {
                    $('head').append('<style rel="stylesheet" iconpicker>' + css + '</style>');
                }
            },
            /**
             * 获取数据
             */
            getData: {
                fontClass: function () {
                    var arr = [
                        "layui-icon-heart-fill",
                        "layui-icon-heart",
                        "layui-icon-light",
                        "layui-icon-time",
                        "layui-icon-bluetooth",
                        "layui-icon-at",
                        "layui-icon-mute",
                        "layui-icon-mike",
                        "layui-icon-key",
                        "layui-icon-gift",
                        "layui-icon-email",
                        "layui-icon-rss",
                        "layui-icon-wifi",
                        "layui-icon-logout",
                        "layui-icon-android",
                        "layui-icon-ios",
                        "layui-icon-windows",
                        "layui-icon-transfer",
                        "layui-icon-service",
                        "layui-icon-subtraction",
                        "layui-icon-addition",
                        "layui-icon-slider",
                        "layui-icon-print",
                        "layui-icon-export",
                        "layui-icon-cols",
                        "layui-icon-screen-restore",
                        "layui-icon-screen-full",
                        "layui-icon-rate-half",
                        "layui-icon-rate",
                        "layui-icon-rate-solid",
                        "layui-icon-cellphone",
                        "layui-icon-vercode",
                        "layui-icon-login-wechat",
                        "layui-icon-login-qq",
                        "layui-icon-login-weibo",
                        "layui-icon-password",
                        "layui-icon-username",
                        "layui-icon-refresh-3",
                        "layui-icon-auz",
                        "layui-icon-spread-left",
                        "layui-icon-shrink-right",
                        "layui-icon-snowflake",
                        "layui-icon-tips",
                        "layui-icon-note",
                        "layui-icon-home",
                        "layui-icon-senior",
                        "layui-icon-refresh",
                        "layui-icon-refresh-1",
                        "layui-icon-flag",
                        "layui-icon-theme",
                        "layui-icon-notice",
                        "layui-icon-website",
                        "layui-icon-console",
                        "layui-icon-face-surprised",
                        "layui-icon-set",
                        "layui-icon-template-1",
                        "layui-icon-app",
                        "layui-icon-template",
                        "layui-icon-praise",
                        "layui-icon-tread",
                        "layui-icon-male",
                        "layui-icon-female",
                        "layui-icon-camera",
                        "layui-icon-camera-fill",
                        "layui-icon-more",
                        "layui-icon-more-vertical",
                        "layui-icon-rmb",
                        "layui-icon-dollar",
                        "layui-icon-diamond",
                        "layui-icon-fire",
                        "layui-icon-return",
                        "layui-icon-location",
                        "layui-icon-read",
                        "layui-icon-survey",
                        "layui-icon-face-smile",
                        "layui-icon-face-cry",
                        "layui-icon-cart-simple",
                        "layui-icon-cart",
                        "layui-icon-next",
                        "layui-icon-prev",
                        "layui-icon-upload-drag",
                        "layui-icon-upload",
                        "layui-icon-download-circle",
                        "layui-icon-component",
                        "layui-icon-file-b",
                        "layui-icon-user",
                        "layui-icon-find-fill",
                        "layui-icon-loading",
                        "layui-icon-loading-1",
                        "layui-icon-add-1",
                        "layui-icon-play",
                        "layui-icon-pause",
                        "layui-icon-headset",
                        "layui-icon-video",
                        "layui-icon-voice",
                        "layui-icon-speaker",
                        "layui-icon-fonts-del",
                        "layui-icon-fonts-code",
                        "layui-icon-fonts-html",
                        "layui-icon-fonts-strong",
                        "layui-icon-unlink",
                        "layui-icon-picture",
                        "layui-icon-link",
                        "layui-icon-face-smile-b",
                        "layui-icon-align-left",
                        "layui-icon-align-right",
                        "layui-icon-align-center",
                        "layui-icon-fonts-u",
                        "layui-icon-fonts-i",
                        "layui-icon-tabs",
                        "layui-icon-radio",
                        "layui-icon-circle",
                        "layui-icon-edit",
                        "layui-icon-share",
                        "layui-icon-delete",
                        "layui-icon-form",
                        "layui-icon-cellphone-fine",
                        "layui-icon-dialogue",
                        "layui-icon-fonts-clear",
                        "layui-icon-layer",
                        "layui-icon-date",
                        "layui-icon-water",
                        "layui-icon-code-circle",
                        "layui-icon-carousel",
                        "layui-icon-prev-circle",
                        "layui-icon-layouts",
                        "layui-icon-util",
                        "layui-icon-templeate-1",
                        "layui-icon-upload-circle",
                        "layui-icon-tree",
                        "layui-icon-table",
                        "layui-icon-chart",
                        "layui-icon-chart-screen",
                        "layui-icon-engine",
                        "layui-icon-triangle-d",
                        "layui-icon-triangle-r",
                        "layui-icon-file",
                        "layui-icon-set-sm",
                        "layui-icon-reduce-circle",
                        "layui-icon-add-circle",
                        "layui-icon-404",
                        "layui-icon-about",
                        "layui-icon-up",
                        "layui-icon-down",
                        "layui-icon-left",
                        "layui-icon-right",
                        "layui-icon-circle-dot",
                        "layui-icon-search",
                        "layui-icon-set-fill",
                        "layui-icon-group",
                        "layui-icon-friends",
                        "layui-icon-reply-fill",
                        "layui-icon-menu-fill",
                        "layui-icon-log",
                        "layui-icon-picture-fine",
                        "layui-icon-face-smile-fine",
                        "layui-icon-list",
                        "layui-icon-release",
                        "layui-icon-ok",
                        "layui-icon-help",
                        "layui-icon-chat",
                        "layui-icon-top",
                        "layui-icon-star",
                        "layui-icon-star-fill",
                        "layui-icon-close-fill",
                        "layui-icon-close",
                        "layui-icon-ok-circle",
                        "layui-icon-add-circle-fine",
                    ];
                    return arr;
                },
                unicode: function () {
                    return [
                        "&ampxe68f;",
                        "&ampxe68c;",
                        "&ampxe748;",
                        "&ampxe68d;",
                        "&ampxe689;",
                        "&ampxe687;",
                        "&ampxe685;",
                        "&ampxe6dc;",
                        "&ampxe683;",
                        "&ampxe627;",
                        "&ampxe618;",
                        "&ampxe808;",
                        "&ampxe7e0;",
                        "&ampxe682;",
                        "&ampxe684;",
                        "&ampxe680;",
                        "&ampxe67f;",
                        "&ampxe691;",
                        "&ampxe626;",
                        "&ampxe67e;",
                        "&ampxe624;",
                        "&ampxe714;",
                        "&ampxe66d;",
                        "&ampxe67d;",
                        "&ampxe610;",
                        "&ampxe758;",
                        "&ampxe622;",
                        "&ampxe6c9;",
                        "&ampxe67b;",
                        "&ampxe67a;",
                        "&ampxe678;",
                        "&ampxe679;",
                        "&ampxe677;",
                        "&ampxe676;",
                        "&ampxe675;",
                        "&ampxe673;",
                        "&ampxe66f;",
                        "&ampxe9aa;",
                        "&ampxe672;",
                        "&ampxe66b;",
                        "&ampxe668;",
                        "&ampxe6b1;",
                        "&ampxe702;",
                        "&ampxe66e;",
                        "&ampxe68e;",
                        "&ampxe674;",
                        "&ampxe669;",
                        "&ampxe666;",
                        "&ampxe66c;",
                        "&ampxe66a;",
                        "&ampxe667;",
                        "&ampxe7ae;",
                        "&ampxe665;",
                        "&ampxe664;",
                        "&ampxe716;",
                        "&ampxe656;",
                        "&ampxe653;",
                        "&ampxe663;",
                        "&ampxe6c6;",
                        "&ampxe6c5;",
                        "&ampxe662;",
                        "&ampxe661;",
                        "&ampxe660;",
                        "&ampxe65d;",
                        "&ampxe65f;",
                        "&ampxe671;",
                        "&ampxe65e;",
                        "&ampxe659;",
                        "&ampxe735;",
                        "&ampxe756;",
                        "&ampxe65c;",
                        "&ampxe715;",
                        "&ampxe705;",
                        "&ampxe6b2;",
                        "&ampxe6af;",
                        "&ampxe69c;",
                        "&ampxe698;",
                        "&ampxe657;",
                        "&ampxe65b;",
                        "&ampxe65a;",
                        "&ampxe681;",
                        "&ampxe67c;",
                        "&ampxe601;",
                        "&ampxe857;",
                        "&ampxe655;",
                        "&ampxe770;",
                        "&ampxe670;",
                        "&ampxe63d;",
                        "&ampxe63e;",
                        "&ampxe654;",
                        "&ampxe652;",
                        "&ampxe651;",
                        "&ampxe6fc;",
                        "&ampxe6ed;",
                        "&ampxe688;",
                        "&ampxe645;",
                        "&ampxe64f;",
                        "&ampxe64e;",
                        "&ampxe64b;",
                        "&ampxe62b;",
                        "&ampxe64d;",
                        "&ampxe64a;",
                        "&ampxe64c;",
                        "&ampxe650;",
                        "&ampxe649;",
                        "&ampxe648;",
                        "&ampxe647;",
                        "&ampxe646;",
                        "&ampxe644;",
                        "&ampxe62a;",
                        "&ampxe643;",
                        "&ampxe63f;",
                        "&ampxe642;",
                        "&ampxe641;",
                        "&ampxe640;",
                        "&ampxe63c;",
                        "&ampxe63b;",
                        "&ampxe63a;",
                        "&ampxe639;",
                        "&ampxe638;",
                        "&ampxe637;",
                        "&ampxe636;",
                        "&ampxe635;",
                        "&ampxe634;",
                        "&ampxe633;",
                        "&ampxe632;",
                        "&ampxe631;",
                        "&ampxe630;",
                        "&ampxe62f;",
                        "&ampxe62e;",
                        "&ampxe62d;",
                        "&ampxe62c;",
                        "&ampxe629;",
                        "&ampxe628;",
                        "&ampxe625;",
                        "&ampxe623;",
                        "&ampxe621;",
                        "&ampxe620;",
                        "&ampxe616;",
                        "&ampxe61f;",
                        "&ampxe61c;",
                        "&ampxe60b;",
                        "&ampxe619;",
                        "&ampxe61a;",
                        "&ampxe603;",
                        "&ampxe602;",
                        "&ampxe617;",
                        "&ampxe615;",
                        "&ampxe614;",
                        "&ampxe613;",
                        "&ampxe612;",
                        "&ampxe611;",
                        "&ampxe60f;",
                        "&ampxe60e;",
                        "&ampxe60d;",
                        "&ampxe60c;",
                        "&ampxe60a;",
                        "&ampxe609;",
                        "&ampxe605;",
                        "&ampxe607;",
                        "&ampxe606;",
                        "&ampxe604;",
                        "&ampxe600;",
                        "&ampxe658;",
                        "&ampx1007;",
                        "&ampx1006;",
                        "&ampx1005;",
                        "&ampxe608;",
                    ];
                }
            }
        };

        a.init();
        return new IconPicker();
    };

    /**
     * 选中图标
     * @param filter lay-filter
     * @param iconName 图标名称，自动识别fontClass/unicode
     */
    IconPicker.prototype.checkIcon = function (filter, iconName) {
        var el = $('*[lay-filter=' + filter + ']'),
            p = el.next().find('.layui-iconpicker-item .layui-icon'),
            c = iconName;

        if (c.indexOf('#xe') > 0) {
            p.html(c);
        } else {
            p.html('').attr('class', 'layui-icon ' + c);
        }
        el.attr('value', c).val(c);
    };

    var iconPicker = new IconPicker();
    exports(_MOD, iconPicker);
});
