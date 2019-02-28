<option value="{{ $v['id'] }}">@for ($i = 0; $i < $v['level'] * 4; $i++) &nbsp; @endfor{{ $v['name'] }}</option>
@if (isset($v['children']))
    @foreach($v['children'] as $v)
            @include('admin.menu', $v)
    @endforeach
@endif