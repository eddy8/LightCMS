@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-form layui-card-header light-search">
            <form>
                <input type="hidden" name="action" value="search">
            @include('admin.searchField', ['data' => App\Model\Admin\EntityField::$searchField])
            <div class="layui-inline">
                <label class="layui-form-label">创建日期</label>
                <div class="layui-input-inline">
                    <input type="text" name="created_at" class="layui-input" id="created_at" value="{{ request()->get('created_at') }}">
                </div>
            </div>
            <div class="layui-inline">
                <button class="layui-btn layuiadmin-btn-list" lay-filter="form-search" id="submitBtn">
                    <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                </button>
            </div>
            </form>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'{{ route('admin::entityField.list') }}?{{ request()->getQueryString() }}', page:true, limit:50, id:'test', toolbar:'<div><a href=\'{{ route('admin::entityField.create') }}?{{ request()->getQueryString() }}\'><i class=\'layui-icon layui-icon-add-1\'></i>新增模型字段</a> | 单击排序值可直接编辑</div>'}" lay-filter="test">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                    @include('admin.listHead', ['data' => App\Model\Admin\EntityField::$listField])
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
    <a href="<% d.editUrl %>" class="layui-table-link"><i class="layui-icon layui-icon-edit"></i></a>
    <a href="javascript:;" class="layui-table-link" title="删除" style="margin-left: 10px" onclick="deleteMenu('<% d.deleteUrl %>')"><i class="layui-icon layui-icon-delete"></i></a>
</script>
<script type="text/html" id="isShowTemplet">
    <input data-id="<% d.id %>" type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否"
    <%# if (d.is_show == 1) { %>
        checked
    <%# } %>
    >
</script>
<script type="text/html" id="isShowInlineTemplet">
    <input data-id="<% d.id %>" type="checkbox" name="is_show_inline" lay-skin="switch" lay-text="是|否"
    <%# if (d.is_show_inline == 1) { %>
        checked
    <%# } %>
    >
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

        var table = layui.table;
        table.on('edit(test)', function(obj){ //注：edit是固定事件名，test是table原始容器的属性 lay-filter="对应的值"
            $.ajax({
                url: '{{ route('admin::entityField.listUpdate', ['id' => '__replace_id']) }}'.replace('__replace_id', obj.data.id),
                method: 'put',
                dataType: 'json',
                data: {order: obj.value},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 3});
                        return false;
                    }
                    layer.msg(result.msg, {icon: 1});
                }
            });
        });

        table.on('tool(test)', function (obj) {
            var event = obj.event, tr = obj.tr;
            var maps = {
                showEvent: "is_show",
                showInlineEvent: "is_show_inline"
            };

            var key = maps[event];
            var val = tr.find("input[name='" + key + "']").prop('checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('admin::entityField.listUpdate', ['id' => '__replace_id']) }}'.replace('__replace_id', obj.data.id),
                method: 'put',
                dataType: 'json',
                data: {[key]: val},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 3});
                        return false;
                    }
                    layer.msg(result.msg, {icon: 1});
                }
            });
        });

        function deleteMenu (url) {
            layer.confirm('确定删除？删除字段将同时删除数据库表字段，请谨慎操作！', function(index){
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
                                location.href = '{!! url()->previous() !!}';
                            }
                        });
                    }
                });

                layer.close(index);
            });
        }
    </script>
@endsection
