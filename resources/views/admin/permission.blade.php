<input type="checkbox" name="permission[{{ $v['id'] }}]" title="{{ $v['name'] }}" value="{{ $v['name'] }}" lay-skin="primary" @if($rolePermissions->pluck('id')->contains($v['id'])) checked @endif>
@if (isset($v['children']))
    @foreach($v['children'] as $v)
            @include('admin.permission', $v)
    @endforeach
@endif