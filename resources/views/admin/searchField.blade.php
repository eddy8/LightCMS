@foreach($data as $k => $v)
    <div class="layui-inline">
        <label class="layui-form-label">{{ $v }}</label>
        <div class="layui-input-inline">
            <input type="text" name="{{ $k }}" autocomplete="off" class="layui-input" value="{{ request()->get($k) }}">
        </div>
    </div>
@endforeach