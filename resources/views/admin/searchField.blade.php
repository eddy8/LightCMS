<script>
    function addLoadEvent(func) {
        var oldonload = window.onload;
        if (typeof window.onload != 'function') {
            window.onload = func;
        } else {
            window.onload = function () {
                oldonload();
                func();
            }
        }
    }
</script>
@foreach($data as $k => $v)
    @if(is_string($v))
        <div class="layui-inline">
            <label class="layui-form-label">{{ $v }}</label>
            <div class="layui-input-inline">
                <input type="text" name="{{ $k }}" autocomplete="off" class="layui-input" value="{{ request()->get($k) }}">
            </div>
        </div>
    @elseif(is_array($v))
        @isset($v['showType'])
            @if($v['showType'] === 'select')
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
            @elseif($v['showType'] === 'datetime')
                <div class="layui-inline">
                    <label class="layui-form-label">{{ $v['title'] }}</label>
                    <div class="layui-input-inline">
                        <input type="text" name="{{ $k }}" class="layui-input" id="{{ $k }}" value="{{ request()->get($k) }}">
                    </div>
                </div>
                <script>
                    addLoadEvent(function () {
                        var laydate = layui.laydate;
                        laydate.render({
                            elem: '#{{ $k }}',
                            range: '~'
                        });
                    })
                </script>
            @endif
        @else
            <div class="layui-inline">
                <label class="layui-form-label">{{ $v['title'] }}</label>
                <div class="layui-input-inline">
                    <input type="text" name="{{ $k }}" autocomplete="off" class="layui-input" value="{{ request()->get($k) }}">
                </div>
            </div>
        @endisset
    @endif
@endforeach