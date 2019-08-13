<option @isset($disabledCategoryIds) @if(in_array($v['id'], $disabledCategoryIds) || (isset($disabledChildren) && !empty($v['path']) && !empty(array_intersect($v['path'], $disabledCategoryIds)))) disabled @endif @endisset value="{{ $v['id'] }}" @isset($model) @if($v['id'] == $model->pid) selected @endif @endisset>@for ($i = 0; $i < $v['level'] * 4; $i++) &nbsp; @endfor{{ $v['name'] }}</option>
@if (isset($v['children']))
    @foreach($v['children'] as $v)
            @include('admin.menu', $v)
    @endforeach
@endif