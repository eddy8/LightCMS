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
            var d = $.parseJSON(resp.responseText), tips = "权限不足或帐户未登录";
            if (d && d.msg) {
                tips = d.msg;
            }
            layer.msg(tips, {shift: 6});
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