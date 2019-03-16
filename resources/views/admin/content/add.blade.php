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
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/neditor.config.js"></script>
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/neditor.all.min.js"> </script>
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/neditor.service.js"></script>
                                <!--建议手动加载语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
                                <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/i18n/zh-cn/zh-cn.js"></script>
                                <script type="text/javascript" src="/public/vendor/neditor/third-party/browser-md5-file.min.js"></script>
                                <script type="text/javascript" src="/public/vendor/neditor/third-party/jquery-1.10.2.min.js"></script>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                    <script name="content" id="editor" type="text/plain" style="height:300px;">{!! $model->{$field->name} ?? '' !!}</script>
                                    </div></div>
                                <script>
                                    //实例化编辑器
                                    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                                    var ue = UE.getEditor('editor');
                                    ue.ready(function(){

                                    });
                                </script>
                                @break
                            @case('reference_category')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block" style="width: 400px;z-index: {{99999 - ($field->order + $field->id)}}">
                                        <select name="{{ $field->name }}" lay-verify="required">
                                            @foreach(App\Repository\Admin\CategoryRepository::tree($entityModel->id) as $v)
                                                @include('admin.category', [$v, 'fieldName' => $field->name])
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @break
                            @case('reference_admin_user')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block" style="width: 400px;z-index: {{99999 - ($field->order + $field->id)}}">
                                        <select name="{{ $field->name }}" lay-verify="required">
                                            @foreach(App\Model\Admin\AdminUser::query()->where('status', App\Model\Admin\AdminUser::STATUS_ENABLE)->orderBy('name')->get(['id', 'name']) as $v)
                                                <option value="{{ $v->id }}" @if($v->id == $model->{$field->name}) selected @endif>{{ $v->name }}</option>
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
                            location.href = '{!! url()->previous() !!}';
                        }
                    });
                }
            });

            return false;
        });
    </script>
@endsection
