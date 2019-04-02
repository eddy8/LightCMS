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
                alert(parse.errors[key][0], {shift: 6});
            }
            return false;
        } else {
            var parse = $.parseJSON(resp.responseText);
            if (parse && parse.err) {
                alert(parse.msg);
            }
            return false;
        }
    },
};
$.ajaxSetup(ajax_options);