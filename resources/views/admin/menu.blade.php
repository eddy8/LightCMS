<option value="{{ $v['id'] }}" @isset($model) @if($v['id'] == $model->pid) selected @endif @endisset>@for ($i = 0; $i < $v['level'] * 4; $i++) &nbsp; @endfor{{ $v['name'] }}</option>
@if (isset($v['children']))
    @foreach($v['children'] as $v)
            @include('admin.menu', $v)
    @endforeach
@endif