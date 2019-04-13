<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
    @php
        $user = \Auth::guard('member')->user();
    @endphp
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    LightCMS
                </div>
                <div class="m-b-md">
                    @foreach($entities as $entity)
                        <a target="_blank" href="{{ route('web::entity.content.list', ['entityId' => $entity->id]) }}">{{ $entity->name }}</a><hr>
                    @endforeach
                </div>
                <div class="m-b-md">
                    @if($user)
                        <span style="margin-right: 20px">欢迎 {{ $user->phone }} !</span><a href="{{ route('member::logout') }}">退出登录</a>
                    @else
                        <a href="{{ route('member::login.show') }}">用户登录</a>
                        <a href="{{ route('admin::login.show') }}">后台登录</a>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
