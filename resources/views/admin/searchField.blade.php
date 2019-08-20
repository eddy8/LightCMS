@foreach($data as $k => $v)
    @if(is_string($v))
        <div class="layui-inline">
            <label class="layui-form-label">{{ $v }}</label>
            <div class="layui-input-inline">
                <input type="text" name="{{ $k }}" autocomplete="off" class="layui-input" value="{{ request()->get($k) }}">
            </div>
        </div>
    @elseif(is_array($v))
        <div class="layui-inline">
            <label class="layui-form-label">{{ $v['title'] }}</label>
            <div class="layui-input-inline">
                <select name="{{ $k }}">
                    <option value="" @if(!request()->has($k)) selected @endif>请选择</option>
                    @foreach($v['enums'] as $ik => $iv)
                        <option value="{{ $ik }}" @if(request()->has($k) && request()->get($k) !== "" && request()->get($k) == $ik) selected @endif>{{ $iv }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
@endforeach