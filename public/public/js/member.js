var $ = layui.$,
    layer = layui.layer,
    csrf_token = $('meta[name=csrf-token]').eq(0).attr('content');

var ajax_options = {
    headers: {'X-CSRF-Token': csrf_token},
    type: 'post',
    dataType: 'json',
    error: function (resp, stat, text) {
        if (window.form_submit) {
            form_submit.prop('disabled', false);
        }
        if (resp.status === 422) {
            var parse = $.parseJSON(resp.responseText);
            if (parse && parse.errors) {
                var key = Object.keys(parse.errors)[0];
                layer.msg(parse.errors[key][0], {shift: 6});
            }
            return false;
        } else if (resp.status === 404) {
            layer.msg('资源不存在', {icon: 5});
            return false;
        } else if (resp.status === 401) {
            layer.msg('请先登录', {shift: 6});
            return false;
        } else if (resp.status === 429) {
            layer.msg('访问过于频繁，请稍后再试', {shift: 6});
            return false;
        } else if (resp.status === 419) {
            layer.msg('非法请求。请刷新页面后重试。', {shift: 6});
            return false;
        } else if (resp.status === 500) {
            layer.msg('内部错误，请联系管理员', {shift: 6});
            return false;
        } else {
            var parse = $.parseJSON(resp.responseText);
            if (parse && parse.err) {
                layer.alert(parse.msg);
            }
            return false;
        }
    },
};
$.ajaxSetup(ajax_options);

function getDateTimeStamp(dateStr)
{
    return Date.parse(dateStr.replace(/-/gi,"/"));
}

function getDateDiff(dateTimeStamp)
{
    var minute = 1000 * 60;
    var hour = minute * 60;
    var day = hour * 24;
    var halfamonth = day * 15;
    var month = day * 30;
    var now = new Date().getTime();
    var diffValue = now - dateTimeStamp;
    if(diffValue < 0){return;}
    var monthC =diffValue/month;
    var weekC =diffValue/(7*day);
    var dayC =diffValue/day;
    var hourC =diffValue/hour;
    var minC =diffValue/minute;
    if(monthC>=1){
        result="" + parseInt(monthC) + "月前";
    }
    else if(weekC>=1){
        result="" + parseInt(weekC) + "周前";
    }
    else if(dayC>=1){
        result=""+ parseInt(dayC) +"天前";
    }
    else if(hourC>=1){
        result=""+ parseInt(hourC) +"小时前";
    }
    else if(minC>=1){
        result=""+ parseInt(minC) +"分钟前";
    }else
        result="刚刚";
    return result;
}