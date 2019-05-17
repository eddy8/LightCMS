<!DOCTYPE html>
<html>
<head>
    <title>{{ $content->title }}</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/public/vendor/layui-v2.4.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/public/css/member.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/css/basic/emojify.css" />
    <!-- 样式文件来自 www.taptap.com 侵删~~~ -->
    <link rel="stylesheet" href="/public/css/app-2adb6bab87.css">
    <style>
        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
        .pagination>li {
            display: inline;
        }
        img {
            vertical-align: middle;
        }
        img {
            border: 0;
        }
        .img-circle {
            border-radius: 50%;
        }

        .taptap-review-item {
            padding: 5px 0px;
        }
    </style>
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

        <!--Title-->
        <div class="font-sans">
                        <h1 class="font-sans break-normal text-black pt-6 pb-2 text-3xl md:text-4xl">{{ $content->title }}</h1>
				<p class="text-sm md:text-base font-normal text-grey-dark">发布于：{{ $content->created_at }} 最后更新：{{ $content->updated_at }}</p>
        </div>
        <div class="mt-6">
            <!--Post Content-->
            {!! $content->content !!}
            <!--/ Post Content-->
        </div>


    </div>

    <!--Tags -->
    <div class="text-base md:text-sm text-grey px-4 py-6">

    </div>

    <!--Divider-->
    <hr class="border-b-2 border-grey-light mb-8 mx-4">

    <!--Next & Prev Links-->
    <div class="font-sans flex justify-between content-center px-4 pb-12">
            <div class="text-left">
                @if($previous)
                <span class="text-xs md:text-sm font-normal text-grey-dark">&lt; 上一篇</span><br>
                <p><a href="{{ route('web::content', ['contentId' => $previous->id, 'entityId' => $entityId]) }}" class="break-normal text-base md:text-sm text-teal font-bold no-underline hover:underline">{{ $previous->title }}</a></p>
                @endif
            </div>
            <div class="text-right">
                @if($next)
                <span class="text-xs md:text-sm font-normal text-grey-dark">下一篇 &gt;</span><br>
                <p><a href="{{ route('web::content', ['contentId' => $next->id, 'entityId' => $entityId]) }}" class="break-normal text-base md:text-sm text-teal font-bold no-underline hover:underline">{{ $next->title }}</a></p>
                @endif
            </div>
    </div>


    <!--/Next & Prev Links-->

    <div id="comment-form">
        <h3>用户评价</h3>
        <form action="{{ route('member::comment.save', ['entityId' => $entityId, 'contentId' => $content->id]) }}" class="layui-form">
            <div class="layui-form-item">
                <div>
                <textarea name="content" rows="7" placeholder="请输入评论内容" class="layui-textarea" id="comment-content"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div>
                    <span id="face">:smirk: :heart_eyes: :kissing_heart: :heartpulse: :two_hearts: :revolving_hearts: :cupid: :sparkling_heart:</span><a target="_blank" href="https://www.webfx.com/tools/emoji-cheat-sheet/" style="margin-left: 5px">更多</a>
                    <button class="layui-btn" style="float: right" lay-submit lay-filter="comment" id="submitBtn">提交</button>
                </div>
            </div>
        </form>
    </div>
    <div id="comments">

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
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script type="text/javascript" src="/public/js/member.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/js/emojify.min.js"></script>
<script>
    var info = {
        'entityId': {{ $entityId }},
        'contentId': {{ $content->id }}
    };
    var form = layui.form;
    var commentIds = [];

    emojify.setConfig({
        img_dir : '/public/image/emoji',
        ignored_tags : {
            'SCRIPT'  : 1,
            'TEXTAREA': 1,
            'A'       : 1,
            'PRE'     : 1,
            'CODE'    : 1
        }
    });
    emojify.run(document.getElementById('face'));
    $("#face img").click(function(){
        $("#comment-content").val($("#comment-content").val() + ' ' + $(this).attr('title'));
    });

    //监听提交
    form.on('submit(comment)', function(data){
        window.form_submit = $('#submitBtn');
        form_submit.prop('disabled', true);
        $.ajax({
            url: data.form.action,
            data: data.field,
            success: function (result) {
                if (result.code !== 0) {
                    form_submit.prop('disabled', false);
                    layer.msg(result.msg, {shift: 6});
                    return false;
                }
                form_submit.prop('disabled', false);
                layer.msg('操作成功', {icon: 1}, function () {
                    if (result.reload) {
                        location.reload();
                    }
                    if (result.redirect) {
                        location.href = '{!! url()->previous() !!}';
                    }
                });
            }
        });

        return false;
    });

    // loadReplyComments(reply, listData[i].id, listData[i].user_id)
    function loadReplyComments(reply, rid, uid)
    {
        var html = "";
        if (reply.data.length > 0) {
                        html = html + '<ul class="list-unstyled taptap-comments-list">';
                        for (var j = 0; j < reply.data.length; j++) {
                            commentIds.push(reply.data[j].id);
                            avatar = reply.data[j].user.avatar;
                            if (avatar === '') {
                                avatar = '/public/image/boy-2.png';
                            }
                            html = html + '<li class="taptap-comment-item " id="comment-10486096"> \
                    <a href="" class="comment-item-avatar img-circle female"> \
                        <img src="' + avatar + '" data-comment-avatar="10486096"> \
                    </a> \
                    <div class="comment-item-text"> \
                        <div class="item-text-header"> \
                            <span class="taptap-user" data-user-id="15226001"> \
                                <a href="" class="taptap-user-name taptap-link" rel="nofollow">' + reply.data[j].user.name + '</a> \
                            </span>';
                            if (reply.data[j].reply_user !== undefined && reply.data[j].reply_user.id !== uid) {
                                html = html + '<i class="taptap-icon icon-reply-right"></i> \
                            <span class="taptap-user" data-user-id="15226001"> \
                                <a href="" class="taptap-user-name taptap-link" rel="nofollow">' + reply.data[j].reply_user.name + '</a> \
                            </span>';
                            }
                            html = html + '</div> \
                        <div class="item-text-body" data-comment-10486096="contents"> \
                            <p>' + reply.data[j].content.replace(/\n/g,"<br>").replace(/\s/g,"&nbsp;") + '</p> \
                        </div> \
                        <div class="item-text-footer"> \
                            <ul class="list-unstyled text-footer-btns"> \
                                <li> \
                                    <span class="text-footer-time" data-dynamic-time="1555341470" title="' + reply.data[j].created_at + '">' + getDateDiff(getDateTimeStamp(reply.data[j].created_at)) + '</span> \
                                </li> \
                                <li class="open"> \
                                    <a href="#" data-taptap-comment="button" data-obj="comment" data-obj-id="' + reply.data[j].id + '" data-reply-id="10486096" class="btn btn-sm taptap-button-opinion comment question-witch-replay"> \
                                        <i class="icon-font icon-reply"></i> \
                                        <span>回复</span> \
                                    </a> \
                                </li> \
                                <li> \
                                    <button class="btn btn-sm taptap-button-opinion vote-btn vote-up" data-value="like" data-id="' + reply.data[j].id + '" data-has-word=""> \
                                        <i class="icon-font icon-up"></i> \
                                        <span data-taptap-ajax-vote="count">' + reply.data[j].like + '</span> \
                                    </button> \
                                </li> \
                                <li> \
                                    <button class="btn btn-sm taptap-button-opinion vote-btn vote-down" data-value="dislike" data-id="' + reply.data[j].id + '" data-has-word=""> \
                                        <i class="icon-font icon-down"></i> \
                                        <span data-taptap-ajax-vote="count">' + reply.data[j].dislike + '</span> \
                                    </button> \
                                </li> \
                                <li> \
                                    <button type="button" data-id="' + reply.data[j].id + '" class="btn btn-sm taptap-button-opinion report"> \
                                        <span>举报</span> \
                                    </button> \
                                </li> \
                            </ul> \
                        </div> \
                    </div> \
                </li>';
                        }
                        html = html + '</ul>';
                    }
                    html = html + '<div class="taptap-comments-buttons"> \
                <div class="comments-buttons-page" data-taptap-ajax="paginator"> ';
                    if (reply.last_page > 1) {
                        html = html + '<section class="taptap-paginator">\
                    <ul class="pagination">';
                        html = html + '<li class="disabled"><span>&lt;</span></li>';
                        if (reply.last_page <= 10) {
                            for (var k = 1; k <= reply.last_page; k++) {
                                if (k == reply.current_page) {
                                    html = html + '<li class="active"><span>' + k + '</span></li>';
                                } else {
                                    html = html + '<li><a data-rid="'+rid+'" data-uid="'+uid+'" class="comment-reply" rel="nofollow" href="'+reply.path+'?rid='+rid+'&page='+k+'">' + k + '</a></li>';
                                }
                            }
                        } else {
                            var endNum = reply.current_page + 5;
                            endNum = reply.last_page > endNum ? endNum : reply.last_page;
                            for (var k = reply.current_page - 5; k < endNum; k++) {

                                if (k == reply.current_page) {
                                    html = html + '<li class="active"><span>' + k + '</span></li>';
                                } else {
                                    html = html + '<li><a data-rid="'+rid+'" data-uid="'+uid+'" class="comment-reply" rel="nofollow" href="'+reply.path+'?rid='+rid+'&page='+k+'">' + k + '</a></li>';
                                }
                            }
                        }
                        html = html + '<li><span>&gt;</span></li>';
                        html = html + '</ul></section> ';
                    }

                    html = html + '</div>';
                    return html;
    }

    function loadComments(url) {
        $.ajax({
            url: url !== undefined ? url : '{{ route('web::comment.list', ['entityId' => $entityId, 'contentId' => $content->id]) }}',
            method: 'get',
            success: function (data) {
                if (data.code !== 0) {
                    layer.msg('评论加载失败', {icon: 2});
                    return;
                }

                var listData = data.data.data, avatar;
                var html = '<ul class="list-unstyled taptap-review-list" id="reviewsList">';
                for (var i = 0; i < listData.length; i++) {
                    commentIds.push(listData[i].id);
                    avatar = listData[i].user.avatar;
                    if (avatar === '') {
                        avatar = '/public/image/boy-2.png';
                    }
                    html = html + '<li id="review-' + listData[i].id + '" class="taptap-review-item collapse in" data-user="' + listData[i].user_id + '"> \
    <a href="#" class="review-item-avatar img-circle gender-empty" rel="nofollow"> \
        <img src="' + avatar + '"> \
    </a> \
    <div class="review-item-text "> \
        <div class="item-text-header"> \
            <span class="taptap-user" data-user-id=""> \
                <a href="#" class="taptap-user-name taptap-link" rel="nofollow">' + listData[i].user.name + '</a> \
            </span> \
            <a href="#" class="text-header-time"> \
                <span data-toggle="tooltip" data-placement="top" title="' + listData[i].created_at + '" \
                        <span>发布于 </span> \
                        <span>' + getDateDiff(getDateTimeStamp(listData[i].created_at)) + '</span> \
                </span> \
            </a> \
            <button type="button" data-obj="review" data-id="' + listData[i].id + '" class="btn btn-sm taptap-button-opinion report"> \
                <span>举报</span> \
            </button> \
        </div> \
        <div class="item-text-body" data-review-' + listData[i].id + '="contents"> \
            <p>' + listData[i].content.replace(/\n/g,"<br>").replace(/\s/g,"&nbsp;") + '</p> \
        </div> \
        <div class="item-text-footer"> \
            <ul class="list-unstyled text-footer-btns"> \
                <li> \
                    <button class="btn btn-sm taptap-button-opinion vote-btn vote-up" data-value="like" data-id="' + listData[i].id + '" data-has-word=""> \
                        <i class="icon-font icon-up"></i> \
                        <span data-taptap-ajax-vote="count">' + listData[i].like + '</span> \
                    </button> \
                </li> \
                <li> \
                    <button class="btn btn-sm taptap-button-opinion vote-btn vote-down" data-value="dislike" data-id="' + listData[i].id + '" data-has-word=""> \
                        <i class="icon-font icon-down"></i> \
                        <span data-taptap-ajax-vote="count">' + listData[i].dislike + '</span> \
                    </button> \
                </li> \
                <li> \
                    <button id="review-' + listData[i].id + '-reply-button" class="btn btn-sm taptap-button-opinion comment question-witch-replay" data-taptap-comment="button" data-obj-id="' + listData[i].id + '" data-modalid="#commentModal"> \
                        <i class="icon-font icon-reply"></i> \
                        <span class="normal-text">回复 ' + listData[i].reply_count + '</span> \
                    </button> \
                </li> \
            </ul> \
        </div> \
        <div class="taptap-comments collapse in" data-taptap-comment="container" data-taptap-ajax-paginator="container">';
                    var reply = listData[i].reply;
                    html = html + loadReplyComments(reply, listData[i].id, listData[i].user_id);
                    html = html + '<span id="reply-13428285-button" class="reply-review-button"></span> \
            </div> \
        </div> \
    </div> \
</li> \
</ul>';
                }

                if (data.data.last_page > 1) {
                    html = html + '<section class="taptap-paginator"><ul class="pagination">';
                    if (data.data.current_page !== 1) {
                        html = html + '<li><a class="page-target" href="' + data.data.prev_page_url + '">上一页</a></li>';
                    }
                    if (data.data.next_page_url !== null) {
                        html = html + '<li><a class="page-target" href="' + data.data.next_page_url + '">下一页</a></li>';
                    }
                    html = html + '</ul></section>';
                }

                $('#comments').html(html);

                emojify.run(document.getElementById('comments'));

                @auth('member')
                // 获取登录用户对评论的操作数据
                commentAction();
                @endauth

                // 评论回复
                $('.question-witch-replay').on('click', function () {
                    $('input[name=pid]').remove();
                    layer.open({
                        type: 1,
                        area: '500px',
                        title: '回复',
                        content: $('form.layui-form').append('<input type="hidden" name="pid" value="' + $(this).data('obj-id') + '">'),
                        cancel: function (index, layero) {
                            $('input[name=pid]').remove();
                            return true;
                        }
                    });
                });

                // 评论操作
                $('div#comments').on('click', 'button.vote-btn', function () {
                    var id = $(this).data('id'),
                        action = $(this).data('value'),
                        that = $(this);
                    if (that.hasClass('active')) {
                        action = 'neutral';
                    }
                    $.ajax({
                        url: '/member/comment/' + id + '/operate/' + action,
                        success: function (d) {
                            if (d.code !== 0) {
                                layer.msg('操作失败', {icon: 2});
                                return;
                            }
                            var btn = $('button.vote-btn[data-id=' + id + ']');
                            btn.removeClass('active').find('span').text(0);
                            if (d.data[action] > 0) {
                                that.addClass('active');
                            }
                            btn.find('span').eq(0).text(d.data['like']);
                            btn.find('span').eq(1).text(d.data['dislike']);
                        }
                    })
                });

                // 举报
                $('div#comments').on('click', 'button.report', function () {
                    layer.msg('待实现');
                });

                // 评论翻页
                $('a.page-target').click(function (e) {
                    console.log(commentIds);
                    e.preventDefault();
                    loadComments($(this).attr('href'));
                });

                // 评论回复翻页
                $('div.taptap-comments').on('click', 'a.comment-reply', function (e) {
                    e.preventDefault();
                    commentIds = [];
                    var url = $(this).attr('href'),
                        rid = $(this).data("rid"),
                        uid = $(this).data("uid"),
                        that = $(this);
                    $.ajax({
                        method: "get",
                        url: url,
                        success: function (d) {
                            that.parents('div.taptap-comments').html(loadReplyComments(d.data, rid, uid));

                            @auth('member')
                            // 获取登录用户对评论的操作数据
                            commentAction();
                            @endauth
                        }
                    });
                });
            },
            error: function () {
                layer.msg('页面错误', {icon: 2});
            }
        });
    }

    function commentAction()
    {
        var commentIdsStr = commentIds.join(',');
        if (commentIdsStr !== '') {
            $.ajax({
                url:'{{ route("member::comment.operateLogs") }}',
                method: 'get',
                data: {comment_ids: commentIdsStr},
                success: function (d) {
                    if (d.code !== 0) {
                        layer.msg('获取评论操作数据失败', {icon: 2});
                        return;
                    }
                    for (var i = d.data.length - 1; i >= 0; i--) {
                        $('button[data-value='+d.data[i].operate+'][data-id='+d.data[i].comment_id+']').addClass('active');
                    }
                }
            })
        }
    }

    loadComments();

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
