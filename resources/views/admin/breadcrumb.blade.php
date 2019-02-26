<div class="layui-card-header">
            <span class="layui-breadcrumb">
                @foreach($breadcrumb as $v)
                    @if($v['url'] != '')
                        <a href="{{ $v['url'] }}">{{ $v['title'] }}</a>
                    @else
                        <a><cite>{{ $v['title'] }}</cite></a>
                    @endif
                @endforeach
            </span>
</div>