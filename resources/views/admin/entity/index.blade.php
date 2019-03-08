@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'{{ route('admin::entity.list') }}?{{ request()->getQueryString() }}', page:true, limit:50, id:'test', toolbar:'<div><a href=\'{{ route('admin::entity.create') }}\'><i class=\'layui-icon layui-icon-add-1\'></i>新增模型</a></div>'}" lay-filter="test">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                    @include('admin.listHead', ['data' => App\Model\Admin\Entity::$listField])
                    <th lay-data="{field:'created_at'}">添加时间</th>
                    <th lay-data="{field:'updated_at'}">更新时间</th>
                    <th lay-data="{width:200, templet:'#action'}">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
<script type="text/html" id="action">
    <a href="<% d.editUrl %>" class="layui-table-link" title="编辑"><i class="layui-icon layui-icon-edit"></i></a>
    <a href="<% d.fieldUrl %>" class="layui-table-link" title="字段管理" style="margin-left: 10px">字段管理</a>
</script>

@section('js')
    <script>
        var laytpl = layui.laytpl;
        laytpl.config({
            open: '<%',
            close: '%>'
        });

        var laydate = layui.laydate;
        laydate.render({
            elem: '#created_at',
            range: '~'
        });

        function deleteMenu (url) {
            layer.confirm('确定删除？', function(index){
                $.ajax({
                    url: url,
                    data: {'_method': 'DELETE'},
                    success: function (result) {
                        if (result.code !== 0) {
                            layer.msg(result.msg, {shift: 6});
                            return false;
                        }
                        layer.msg(result.msg, {icon: 1}, function () {
                            if (result.reload) {
                                location.reload();
                            }
                            if (result.redirect) {
                                location.href = result.redirect;
                            }
                        });
                    }
                });

                layer.close(index);
            });
        }
    </script>
@endsection
