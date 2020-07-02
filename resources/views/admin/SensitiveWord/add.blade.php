@extends('admin.base')

@section('content')
    <div class="layui-card">

        @include('admin.breadcrumb')

        <div class="layui-card-body">
            <form class="layui-form" action="@if(isset($id)){{ route('admin::SensitiveWord.update', ['id' => $id]) }}@else{{ route('admin::SensitiveWord.save') }}@endif" method="post">
                @if(isset($id)) {{ method_field('PUT') }} @endif
                <div class="layui-form-item">
                    <label class="layui-form-label">专有词</label>
                    <div class="layui-input-block">
                        <input type="text" name="exclusive" autocomplete="off" class="layui-input" value="{{ $model->exclusive ?? ''  }}" placeholder="一般情况只需添加自定义的专有词即可，名词、动词可以不用管">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">动词</label>
                    <div class="layui-input-block">
                        <input type="text" name="verb" autocomplete="off" class="layui-input" value="{{ $model->verb ?? ''  }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">名词</label>
                    <div class="layui-input-block">
                        <input type="text" name="noun" autocomplete="off" class="layui-input" value="{{ $model->noun ?? ''  }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formAdminUser" id="submitBtn">提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var form = layui.form;

        //监听提交
        form.on('submit(formAdminUser)', function(data){
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
                    layer.msg(result.msg, {icon: 1}, function () {
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
    </script>
@endsection
