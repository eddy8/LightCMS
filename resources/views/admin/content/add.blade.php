@extends('admin.base')

@section('content')
    <script>
        function addLoadEvent(func) {
            var oldonload = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = func;
            } else {
                window.onload = function () {
                    oldonload();
                    func();
                }
            }
        }
    </script>
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
                                        <input type="text" name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif autocomplete="off" class="layui-input" value="{{ $model->{$field->name} ?? ''  }}" @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
                                    </div>
                                </div>
                                @break
                            @case('textArea')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        <textarea name="{{ $field->name }}" placeholder="请输入内容" class="layui-textarea" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>{{ $model->{$field->name} ?? ''  }}</textarea>
                                    </div>
                                </div>
                                @break
                            @case('richText')
                                @if(!isset($neditor_init))
                                    @php
                                        $neditor_init = true
                                    @endphp
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/neditor.config.js"></script>
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/neditor.all.min.js"> </script>
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/neditor.service.js"></script>
                                <!--建议手动加载语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
                                <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
                                <script type="text/javascript" charset="utf-8" src="/public/vendor/neditor/i18n/zh-cn/zh-cn.js"></script>
                                <script type="text/javascript" src="/public/vendor/neditor/third-party/browser-md5-file.min.js"></script>
                                <script type="text/javascript" src="/public/vendor/neditor/third-party/jquery-1.10.2.min.js"></script>
                                @endif
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                    <script name="{{ $field->name }}" id="editor-{{ $field->name }}" type="text/plain" style="height:600px;">{!! $model->{$field->name} ?? '' !!}</script>
                                    </div></div>
                                <script>
                                    //实例化编辑器
                                    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                                    var ue_{{ $field->name }} = UE.getEditor('editor-{{ $field->name }}');
                                    ue_{{ $field->name }}.ready(function(){
                                        @if(isset($model) && $field->is_required == \App\Model\Admin\EntityField::EDIT_DISABLE)
                                        ue_{{ $field->name }}.setDisabled();
                                        @endif
                                    });
                                </script>
                                @break
                            @case('password')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        <input type="password" name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif autocomplete="off" class="layui-input" value="{{ $model->{$field->name} ?? ''  }}" @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
                                    </div>
                                </div>
                                @break
                            @case('upload')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        <button type="button" class="layui-btn" id="file-upload-{{ $field->name }}" @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled style="background-color: gray" @endif>
                                            <i class="layui-icon">&#xe67c;</i>上传图片
                                        </button>
                                        <script type="text/javascript">
                                            addLoadEvent(function () {
                                                layui.use('upload', function(){
                                                    var upload = layui.upload;

                                                    //执行实例
                                                    var uploadInst = upload.render({
                                                        elem: '#file-upload-{{ $field->name }}' //绑定元素
                                                        ,url: "{{ route('admin::neditor.serve', ['type' => 'uploadimage']) }}" //上传接口
                                                        ,done: function(res){
                                                            $('input[name={{ $field->name }}]').val(res.url);
                                                        }
                                                        ,error: function(){
                                                            layer.msg('上传失败')
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                        <div style="float: left;width: 50%">
                                        <input type="input" name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif autocomplete="off" class="layui-input" value="{{ $model->{$field->name} ?? ''  }}" @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif></div>
                                    </div>
                                </div>
                                @break
                            @case('reference_category')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block" style="width: 400px;z-index: {{99999 - ($field->order + $field->id)}}">
                                        <select name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
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
                                        <select name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
                                            @foreach(App\Model\Admin\AdminUser::query()->where('status', App\Model\Admin\AdminUser::STATUS_ENABLE)->orderBy('name')->get(['id', 'name']) as $v)
                                                <option value="{{ $v->id }}" @if(isset($model) && $v->id == $model->{$field->name}) selected @endif>{{ $v->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @break
                                        @case('datetime')
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">{{ $field->form_name }}</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="{{ $field->name }}" class="layui-input" id="{{ $field->name }}" value="{{ $model->{$field->name} ?? '' }}">
                                            </div>
                                        </div>
                                        <script>
                                            addLoadEvent(function () {
                                                var laydate = layui.laydate;
                                                laydate.render({
                                                    elem: '#{{ $field->name }}',
                                                    type: 'datetime'
                                                });
                                            });
                                        </script>
                                        @break
                                        @case('date')
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">{{ $field->form_name }}</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="{{ $field->name }}" class="layui-input" id="{{ $field->name }}" value="{{ $model->{$field->name} ?? '' }}">
                                            </div>
                                        </div>
                                        <script>
                                            addLoadEvent(function () {
                                                var laydate = layui.laydate;
                                                laydate.render({
                                                    elem: '#{{ $field->name }}',
                                                });
                                            });
                                        </script>
                                        @break
                            @case('checkbox')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        @foreach(parseEntityFieldParams($field->form_params) as $v)
                                            <input type="checkbox" name="{{ $field->name }}[]" value="{{ $v[0] }}" title="{{ $v[1] }}" lay-skin="primary" @if(isset($model) && isChecked($v[0], $model->{$field->name})) checked @endif @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
                                        @endforeach
                                    </div>
                                </div>
                                @break
                            @case('option')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block">
                                        @foreach(parseEntityFieldParams($field->form_params) as $v)
                                            <input type="radio" name="{{ $field->name }}" value="{{ $v[0] }}" title="{{ $v[1] }}" @if((isset($model) && $v[0] == $model->{$field->name}) || $loop->first) checked @endif @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
                                        @endforeach
                                    </div>
                                </div>
                                @break
                            @case('select')
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{{ $field->form_name }}</label>
                                    <div class="layui-input-block" style="width: 400px;z-index: {{99999 - ($field->order + $field->id)}}">
                                        <select name="{{ $field->name }}" @if($field->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif @if(isset($model) && $field->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>
                                        @foreach(parseEntityFieldParams($field->form_params) as $v)
                                            <option value="{{ $v[0] }}" @if(isset($model) && $v[0] == $model->{$field->name}) selected @endif>{{ $v[1] }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
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

@section('foot_js')
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
