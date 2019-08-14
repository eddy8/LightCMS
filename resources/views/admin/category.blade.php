<option @isset($disabledCategoryIds) @if(in_array($v['id'], $disabledCategoryIds)) disabled @endif @endisset value="{{ $v['id'] }}" @isset($model) @if($v['id'] == $model->$fieldName) selected @endif @endisset>@for ($i = 0; $i < $v['level'] * 4; $i++) &nbsp; @endfor{{ $v['name'] }}</option>
@if (isset($v['children']))
    @foreach($v['children'] as $v)
            @include('admin.category', [$v, $fieldName])
    @endforeach
@endif