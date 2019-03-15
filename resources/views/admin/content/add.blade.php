@extends('admin.base')

@section('content')
    <div class="layui-card">

        @include('admin.breadcrumb')

        <div class="layui-card-body">
            <form class="layui-form" action="@if(isset($id)){{ route('admin::content.update', ['id' => $id, 'entity' => $entity]) }}@else{{ route('admin::content.save', ['entity' => $entity]) }}@endif" method="post">
                @if(isset($id)) {{ method_field('PUT') }} @endif
                    @foreach($entityFields as $field)
                        @if($field->is_show == \App\Model\Admin\EntityField::SHOW_DISABLE)
                            @continue
                        @endif
                        @switch($field->form_type)
                            @case('input')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif autocomplete="off" class="layui-input" value="{{ $model->{$field->name} ?? ''  }}">
                                    </div>
                                </div>
                                @break
                            @case('textArea')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        <textarea name="{{ $field->name }}" placeholder="请输入内容" class="layui-textarea" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif>{{ $model->{$field->name} ?? ''  }}</textarea>
                                    </div>
                                </div>
                                @break
                            @case('richText')
                                @break
                            @case('reference_category')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block" style="width: 400px">
                                        <select name="{{ $field->name }}" lay-verify="required">
                                            @foreach(App\Repository\Admin\CategoryRepository::tree($entityModel->id) as $v)
                                                @include('admin.menu', $v)
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @break
                            @case('reference_admin_user')
                                @break

                        @endswitch
                    @endforeach
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
                            location.href = result.redirect;
                        }
                    });
                }
            });

            return false;
        });
    </script>
@endsection
