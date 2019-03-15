@extends('admin.base')

@section('content')
    <div class="layui-card">

        @include('admin.breadcrumb')

        <div class="layui-card-body">
            <div>当前角色：<span class="layui-badge layui-bg-green">{{ $role->name }}</span></div>
            <form class="layui-form" action="{{ route('admin::role.permission.update', ['id' => $id]) }}" method="post">
                {{ method_field('PUT') }}
                        @foreach(App\Repository\Admin\MenuRepository::group() as $k => $v)
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <input type="checkbox" name="" title="{{ $k ? $k : '未分组' }}" value="{{ $loop->iteration }}" lay-skin="primary" lay-filter="group">
                        </div>
                    </div>
                            <div class="layui-form-item" style="margin-left: 50px" data-group="{{ $loop->iteration }}">
                            @foreach($v as $menu)
                                    <div class="layui-inline">
                                        <input type="checkbox" name="permission[{{ $menu->id }}]" title="{{ $menu->name }}" value="{{ $menu->name }}" lay-skin="primary" @if($rolePermissions->pluck('id')->contains($menu->id)) checked @endif>
                                    </div>
                            @endforeach
                            </div>
                        @endforeach
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formAdminUser" id="submitBtn">提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var form = layui.form;

        //监听提交
        form.on('submit(formAdminUser)', function(data){
            window.form_submit = $('#submitBtn');
            form_submit.prop('disabled', true);
            $.ajax({
                url: data.form.action,
                data: data.field,
                success: function (result) {
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        layer.msg(result.msg, {shift: 6});
                        return false;
                    }
                    layer.msg(result.msg, {icon: 1}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                        if (result.redirect) {
                            location.href = '{!! url()->previous() !!}';
                        }
                    });
                }
            });

            return false;
        });

        form.on('checkbox(group)', function(data){
            var checked = data.elem.checked;
            $("div[data-group=" + data.value + "]").find('input[type=checkbox]').each(function (i, obj) {
                obj.checked = checked;
            });
            form.render('checkbox');
        });
    </script>
@endsection