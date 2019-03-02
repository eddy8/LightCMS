<div class="layui-form-item" style="margin-left: {{ $v['level'] * 50 }}px">
<div class="layui-input-block">
<input type="checkbox" name="permission[{{ $v['id'] }}]" title="{{ $v['name'] }}" value="{{ $v['name'] }}" lay-skin="primary" @if($rolePermissions->pluck('id')->contains($v['id'])) checked @endif>
</div>
</div>
@if (isset($v['children']))
    @foreach($v['children'] as $v)
            @include('admin.permission', $v)
    @endforeach
@endif