<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/public/vendor/layui-v2.4.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/public/css/member.css">
</head>
<body class="bg-grey-lighter h-screen">
@php
    $type = request()->get('type', 'login');
@endphp
<div class="container h-full flex justify-center items-center">
    <div class="layui-tab w-1/3" >
        <ul class="layui-tab-title">
            <li @if($type === 'login') class="layui-this" @endif>登录</li>
            <li @if($type === 'register') class="layui-this" @endif>注册</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item @if($type === 'login') layui-show @endif">
                <form action="{{ route('member::login') }}" class="w-full max-w-xs" id="login">
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label for="phone" class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                                手机号
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="bg-grey-lighter appearance-none border-2 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple" id="phone" name="phone" type="text" value="">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label for="password" class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                                密码
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="bg-grey-lighter appearance-none border-2 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple" id="password" name="password" type="password" value="">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3"></div>
                        <div class="md:w-2/3">
                            <button class="shadow bg-purple-dark hover:bg-purple-light focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit" id="submit-login">
                                登录
                            </button>
                        </div>
                    </div>
                    <div class="md:flex md:items-center">
                        <div class="md:w-1/3"></div>
                        <div class="md:w-2/3">
                            <a href="{{ route('member::qq.auth') }}" class="py-2 px-4">QQ</a>
                            <a href="{{ route('member::wechat.auth') }}" class="py-2 px-4">微信</a>
                            <a href="{{ route('member::weibo.auth') }}" class="py-2 px-4">微博</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="layui-tab-item @if($type === 'register') layui-show @endif">
                <form action="{{ route('member::register') }}" class="w-full max-w-xs" id="register">
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label for="phone" class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                                手机号
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="bg-grey-lighter appearance-none border-2 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple" id="phone" name="phone" type="text" value="">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label for="password" class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                                密码
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="bg-grey-lighter appearance-none border-2 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple" id="password" name="password" type="password" value="">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label for="repeat_password" class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                                确认密码
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="bg-grey-lighter appearance-none border-2 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple" id="repeat_password" name="repeat_password" type="password" value="">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3"></div>
                        <div class="md:w-2/3">
                            <button class="shadow bg-purple-dark hover:bg-purple-light focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit" id="submit-register">
                                注册
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script type="text/javascript" src="/public/js/member.js"></script>
<script>
    $(function () {
        $('button').click(function () {
            var form = $('#login');
            if ($(this).attr('id') === 'submit-register') {
                form = $('#register');
            }

            window.form_submit = $(this);
            form_submit.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
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
                            location.href = '/';
                        }
                    });
                }
            });
            return false;
        });
    });
</script>
</body>
</html>