@foreach($data as $k => $v)
    <div class="layui-inline">
        <th lay-data="{field:'{{ $k }}'}">{{ $v }}</th>
    </div>
@endforeach