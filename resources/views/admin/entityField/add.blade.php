@extends('admin.base')

@section('content')
    <div class="layui-card">

        @include('admin.breadcrumb')

        <div class="layui-card-body">
            <form class="layui-form" action="@if(isset($id)){{ route('admin::entityField.update', ['id' => $id]) }}@else{{ route('admin::entityField.save') }}@endif" method="post">
                @if(isset($id)) {{ method_field('PUT') }} <i class="layui-icon layui-icon-tips" style="color: red; margin-right: 10px"></i>由于字段修改操作具有一定危险性（可能会影响数据完整性），因此暂未实现直接修改模型的数据库表结构<hr class="layui-bg-red">@endif
                    <div class="layui-form-item">
                        <label class="layui-form-label">模型</label>
                        <div class="layui-input-block">
                            <select name="entity_id" @if(isset($id)) disabled @endif>
                            @foreach($entity as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                            </select>

                        </div>
                    </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">字段名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $model->name ?? ''  }}">
                    </div>
                </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">字段类型</label>
                        <div class="layui-input-inline" style="width: 400px">
                            <select name="type" lay-verify="required" lay-filter="type">
                                @foreach(config('light.db_table_field_type') as $v)
                                    <option value="{{ $v }}" @if(isset($model) && $model->type == $v) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>
                            <div id="str_length" style="display: none">
                            <input type="number" name="field_length" value="" placeholder="对于char、string类型的字段，请在此输入字段长度，默认值255" class="layui-input">
                            </div>
                            <div id="float_length" style="display: none">
                            <input type="number" name="field_total" value="" placeholder="对于浮点数类型的字段，请在此输入总位数，默认值11" class="layui-input">
                            <input type="number" name="field_scale" value="" placeholder="对于浮点数类型的字段，请在此输入小数位数，默认值2" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">字段注释</label>
                        <div class="layui-input-block">
                            <input type="text" name="comment" autocomplete="off" class="layui-input" value="{{ $model->comment ?? ''  }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">表单名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="form_name" required  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $model->form_name ?? ''  }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">表单类型</label>
                        <div class="layui-input-block">
                            <input type="text" name="form_type" required  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $model->form_type ?? ''  }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">表单备注</label>
                        <div class="layui-input-block">
                            <input type="text" name="form_comment" autocomplete="off" class="layui-input" value="{{ $model->form_comment ?? ''  }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                        <label class="layui-form-label">是否显示</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否" value="1" @if(!isset($model) || isset($model) && $model->is_show == App\Model\Admin\EntityField::SHOW_ENABLE) checked @endif>
                        </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">是否可编辑</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="is_edit" lay-skin="switch" lay-text="是|否" value="1" @if(!isset($model) || isset($model) && $model->is_edit == App\Model\Admin\EntityField::EDIT_ENABLE) checked @endif>
                            </div>
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
                            location.href = result.redirect;
                        }
                    });
                }
            });

            return false;
        });

        form.on('select(type)', function(data){
            if (data.value === 'char' || data.value === 'string') {
                $('#str_length').show();
                $('#float_length').hide();
            } else if (data.value === 'float' || data.value === 'double' || data.value === 'decimal' || data.value === 'unsignedDecimal') {
                    $('#str_length').hide();
                    $('#float_length').show();
            } else {
                $('#str_length').hide();
                $('#float_length').hide();
            }
        });

        layui.event.call(null, 'form', 'select(type)', {'value': $('select[name=type]').val()});
    </script>
@endsection
