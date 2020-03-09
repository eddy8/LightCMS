@if(!isset($selects_init))
    @php
        // select多选组件使用可参考 https://github.com/hnzzmsf/layui-formSelects
        $selects_init = true
    @endphp
    <link rel="stylesheet" type="text/css" href="/public/vendor/layui-v2.4.5/plugins/formSelects-v4.css"/>
    <script type="text/javascript" src="/public/vendor/neditor/third-party/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/public/vendor/layui-v2.4.5/plugins/formSelects-v4.min.js"></script>
@endif
<div class="layui-inline">
    <label class="layui-form-label">{{ $inlineField->form_name }}</label>
    <div class="layui-input-inline" style="width: 380px;z-index: {{99999 - ($field->order + $field->id)}}">
        <select @if($selectMultiItem === false) xm-select-radio @endif xm-select-search="{{$inlineField->form_params}}" xm-select="select-{{ $inlineField->name }}" name="{{ $inlineField->name }}" @if($inlineField->is_required == \App\Model\Admin\EntityField::REQUIRED_ENABLE) required  lay-verify="required" @endif @if(isset($model) && $inlineField->is_edit == \App\Model\Admin\EntityField::EDIT_DISABLE) disabled @endif>

        </select>
    </div>
</div>
<script>
    formSelects.render('select-{{ $inlineField->name }}');
    formSelects.config('select-{{ $inlineField->name }}', {
        data: {"value": "@if(isset($model)){{ $model->{$inlineField->name} }}@else{{$inlineField->form_default_value}}@endif"},
    });
</script>