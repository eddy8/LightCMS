@extends('admin.base')

@section('title', '首页')

@section('content')
        <div class="layui-row">
            <div class="layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header"><h2>Web服务器</h2></div>
                    <div class="layui-card-body">
                        {{ request()->server('SERVER_SOFTWARE') }}
                    </div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header"><h2>PHP</h2></div>
                    <div class="layui-card-body">
                        php/{{ phpversion() }} {{ App::VERSION() }} <br>
                        GD Library: {{ gd_info()['GD Version'] }} <br>
                        OPCache: {{ $opcache }} <br>
                        Memory Limit: {{ ini_get('memory_limit') }} <br>
                        Laravel: {{ \App::version() }}
                    </div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header"><h2>数据库</h2></div>
                    <div class="layui-card-body">
                        {{ \DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION) }}
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('js')
    <script>
    </script>
@endsection