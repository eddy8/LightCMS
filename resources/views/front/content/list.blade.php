<!DOCTYPE html>
<html>
<head>
    <title>列表</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/public/css/member.css">
</head>
<body class="bg-grey-lightest font-sans leading-normal tracking-normal">

<nav id="header" class="fixed w-full z-10 pin-t">
    <div id="progress" class="h-1 z-20 pin-t" style="background:linear-gradient(to right, #4dc0b5 var(--scroll), transparent 0);"></div>
    <div class="w-full md:max-w-md mx-auto flex flex-wrap items-center justify-between mt-0 py-3">

        <div class="pl-4">
            <a class="text-black text-base no-underline hover:no-underline font-extrabold text-xl"  href="/">
                LightCMS
            </a>
        </div>

        <div class="block lg:hidden pr-4">
            <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-grey border-grey-dark hover:text-black hover:border-teal appearance-none focus:outline-none">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
            </button>
        </div>

        <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-grey-lightest md:bg-transparent z-20" id="nav-content">
            <ul class="list-reset lg:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    @auth('member')
                        <span>{{ \Auth::guard('member')->user()->name }}</span>
                        <a class="inline-block text-grey-dark no-underline hover:text-black hover:text-underline py-2 px-4" href="{{ route('member::logout') }}">退出</a>
                    @else
                    <a class="inline-block text-grey-dark no-underline hover:text-black hover:text-underline py-2 px-4" href="{{ route('member::login.show') }}">登录</a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--Container-->
<div class="container w-full md:max-w-md mx-auto pt-20">

    <div class="w-full px-4 md:px-6 text-xl text-grey-darkest leading-normal" style="font-family:Georgia,serif;">
        <div class="mt-6">
        @foreach($contents as $content)
            <a href="{{ route('web::content', ['entityId' => $entity->id, 'contentId' => $content->id]) }}">{{ $content->title }}</a><hr>
        @endforeach
        </div>
    </div>

</div>
<!--/container-->

<footer class="bg-white border-t border-grey-light shadow">
    <div class="container max-w-md mx-auto flex py-8">

        <div class="w-full mx-auto flex flex-wrap">
            <div class="flex w-full md:w-1/2 ">
                <div class="px-8">
                    <h3 class="font-bold text-black">关于</h3>
                    <p class="py-4 text-grey-dark text-sm">
                        lightCMS是一个基于Laravel开发的轻量级CMS系统，也可以作为一个通用的后台管理框架使用。
                    </p>
                </div>
            </div>

            <div class="flex w-full md:w-1/2">
                <div class="px-8">
                    <h3 class="font-bold text-black">链接</h3>
                    <ul class="list-reset items-center text-sm pt-3">
                        <li>
                            <a class="inline-block text-grey-dark no-underline hover:text-black hover:text-underline py-1" href="https://github.com/eddy8/lightCMS" target="_blank">GitHub</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>



    </div>
</footer>
<script>
    /* Progress bar */
    //Source: https://alligator.io/js/progress-bar-javascript-css-variables/
    var h = document.documentElement,
        b = document.body,
        st = 'scrollTop',
        sh = 'scrollHeight',
        progress = document.querySelector('#progress'),
        scroll;
    var scrollpos = window.scrollY;
    var header = document.getElementById("header");
    var navcontent = document.getElementById("nav-content");

    document.addEventListener('scroll', function() {

        /*Refresh scroll % width*/
        scroll = (h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100;
        progress.style.setProperty('--scroll', scroll + '%');

        /*Apply classes for slide in bar*/
        scrollpos = window.scrollY;

        if(scrollpos > 10){
            header.classList.add("bg-white");
            header.classList.add("shadow");
            navcontent.classList.remove("bg-grey-lightest");
            navcontent.classList.add("bg-white");
        }
        else {
            header.classList.remove("bg-white");
            header.classList.remove("shadow");
            navcontent.classList.remove("bg-white");
            navcontent.classList.add("bg-grey-lightest");

        }

    });


    //Javascript to toggle the menu
    document.getElementById('nav-toggle').onclick = function(){
        document.getElementById("nav-content").classList.toggle("hidden");
    }
</script>
</body>
</html>
