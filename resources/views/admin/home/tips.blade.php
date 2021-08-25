@extends('admin.base')

@section('title', '温馨提示')

@section('content')
        <div class="layui-row">
            <div class="layui-col-md6 layui-col-md-offset3">
                <div class="layui-card">
                    <div class="layui-card-header"><h1>温馨提示</h1></div>
                    <div class="layui-card-body">
                        @if ($errors->any())
                            @foreach($errors->all() as $error)
                            <h2 style="color:#FF5722; margin-bottom: 10px">{{ $error }}</h2>
                            @endforeach
                        @endif
                        <div style="margin-top: 20px">
                            <a href="{{ url()->previous() }}" class="layui-btn">返回</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('js')
    <script>
        @if ($errors->any())
            layer.msg('{{ implode(" | ", $errors->all())}}', {shift: 6});
        @endif
    </script>
@endsection