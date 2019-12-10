<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登陆 - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/public/vendor/layui-v2.4.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/public/admin/css/lightcms-login.css" media="all">
</head>
<body>

<div class="lightcms-user-login lightcms-user-display-show" id="user-login" style="display: none;">

    <div class="lightcms-user-login-main">
        <div class="lightcms-user-login-box lightcms-user-login-header">
            <h2>后台登陆</h2>
        </div>
        <form id="form">
        <div class="lightcms-user-login-box lightcms-user-login-body layui-form">
            <div class="layui-form-item">
                <label class="lightcms-user-login-icon layui-icon layui-icon-username" for="login-username"></label>
                <input type="text" name="name" id="login-username" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="lightcms-user-login-icon layui-icon layui-icon-password" for="login-password"></label>
                <input type="password" name="password" id="login-password" lay-verify="required" placeholder="密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="lightcms-user-login-icon layui-icon layui-icon-vercode" for="login-vercode"></label>
                        <input type="text" name="captcha" id="login-vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="{{ captcha_src() }}" class="lightcms-user-login-codeimg" id="get-vercode" title="点击刷新验证码" onclick="$(this).prop('src', $(this).prop('src').split('?')[0] + '?' + Math.random())">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="login-submit" type="submit">登 录</button>
            </div>
        </div>
        </form>
    </div>

    <div class="layui-trans lightcms-user-login-footer">

        <p>© 2019 <a href="/" target="_blank">{{ config('app.name') }}</a></p>
    </div>

</div>

<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script>
    $('#form').submit(function () {
        window.form_submit = $('#form').find('[type=submit]');
        form_submit.prop('disabled', true);
        $.ajax({
            url: '{{ route('admin::login') }}',
            data: $('#form').serializeArray(),
            success: function (result) {
                if (result.code !== 0) {
                    form_submit.prop('disabled', false);
                    layer.msg(result.msg, {shift: 6});
                    return false;
                }
                layer.msg(result.msg, {icon: 1}, function () {
                    if (result.reload) {
                        location.reload();
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin::index') }}';
                    }
                });
            },
            complete: function (d) {
                if (d.responseText.indexOf('"errors"') >= 0) {
                    $('#get-vercode').click();
                }
            }
        });
        return false;
    });
</script>
</body>
</html>