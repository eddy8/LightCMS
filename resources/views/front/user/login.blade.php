<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/public/css/member.css">
</head>
<body class="bg-grey-lighter h-screen font-serif">
<div class="container h-full flex justify-center items-center">

<form action="" class="w-full max-w-xs" id="login">
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
    <div class="md:flex md:items-center">
        <div class="md:w-1/3"></div>
        <div class="md:w-2/3">
            <button class="shadow bg-purple-dark hover:bg-purple-light focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit" id="submit">
                登录
            </button>
        </div>
    </div>
</form>
</div>
<script type="text/javascript" src="/public/vendor/neditor/third-party/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/public/js/member.js"></script>
<script>
    $(function () {
        $('#submit').click(function () {
            $.ajax({
                url: '{{ route('member::login') }}',
                data: $('#login').serialize(),
                success: function (d) {
                    console.log(d);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>