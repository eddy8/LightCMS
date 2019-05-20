@foreach($data as $k => $v)
    <div class="layui-inline">
        @if(is_array($v))
        <th lay-data="{field:'{{ $k }}' @foreach($v as $field => $value) @if($field !== 'title') ,{{ $field }}:@if(is_string($value)) '{{ $value }}' @elseif($value === true) true @elseif($value === false) false @else {{ $value }} @endif @endif @endforeach}">{{ $v['title'] }}</th>
        @else
        <th lay-data="{field:'{{ $k }}'}">{{ $v }}</th>
        @endif
    </div>
@endforeach