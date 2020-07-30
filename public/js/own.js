function sendAjax(params) {
    var $ = layui.$;
    var layer = layui.layer;

    var url = params.url;
    var type = params.type || 'post';
    var data = params.data || '';
    var closeLayerCallBack;
    var successCallBack;
    var errorCallBack;
    var completeCallBack;

    if (typeof params.closeLayerCallBack !== 'undefined') {
        closeLayerCallBack = params.closeLayerCallBack;
    } else {
        closeLayerCallBack = function (index) {
            layer.closeAll();
            parent.layer.closeAll();
            parent.location.reload();
        };
    }

    if (typeof params.successCallBack !== 'undefined') {
        successCallBack = params.successCallBack;
    } else {
        successCallBack = function (data) {
            if (data.code == 2000000) {
                layer.open({
                    content: data.message,
                    area: ['300px', '150px'],
                    btn: ['关闭'],
                    yes: closeLayerCallBack,
                    cancel: closeLayerCallBack,
                });
            } else {
                layer.msg(data.message, {icon: 5, time: 3000});
            }
        };
    }

    if (typeof errorCallBack !== 'undefined') {
        errorCallBack = params.errorCallBack;
    } else {
        errorCallBack = function (jqXHR, textStatus, errorThrown) {
            var errorMessage = "Error: " + jqXHR.responseJSON.message;
            layer.msg(errorMessage, {icon: 5, time: 3000});
        };
    }

    if (typeof completeCallBack !== 'undefined') {
        completeCallBack = params.completeCallBack;
    } else {
        completeCallBack = function () {
        };
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: type,
        data: data,
        dataType: "json",
        success: successCallBack,
        error: errorCallBack,
        complete: completeCallBack,
    });
}

function convertTimestamp(date) {
    console.log(date);
    if (date == 0) {
        return date;
    }

    date = date + ''
    if (date.length > 10) {
        date = date.substring(0, 10);
    }
    // 如果date为13位不需要乘1000
    var date = new Date(date * 1000);
    var Y = date.getFullYear() + '-';
    var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
    var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
    var h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
    var m = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
    var s = (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds());
    return Y + M + D + h + m + s;
}

function str_repeat(str, num) {
    return new Array(num + 1).join(str);
}

function to(url) {
    layer.load(1);
    window.location.href = url;
}
