@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-form layui-card-header light-search">
            <form>
                <input type="hidden" name="action" value="search">
                @include('admin.searchField', ['data' => App\Model\Admin\Menu::$searchField])
            <div class="layui-inline">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" autocomplete="off" class="layui-input" value="{{ request()->get('name') }}">
                </div>
            </div>
                <div class="layui-inline">
                    <label class="layui-form-label">路由</label>
                    <div class="layui-input-inline">
                        <input type="text" name="route" autocomplete="off" class="layui-input" value="{{ request()->get('route') }}">
                    </div>
                </div>
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
            <table class="layui-table" lay-data="{url:'{{ route('admin::menu.list') }}?{{ request()->getQueryString() }}', page:true, limit:50, id:'test', toolbar:'<div><a href=\'{{ route('admin::menu.create') }}\'><i class=\'layui-icon layui-icon-add-1\'></i>新增菜单</a><a href=\'javascript:;\' style=\'margin-left:15px\' id=\'discovery\'><i class=\'layui-icon layui-icon-refresh\'></i>自动更新菜单</a></div>'}" lay-filter="test">
                <thead>
                <tr>
                    <th lay-data="{width:50, type:'checkbox'}"></th>
                    <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                    <th lay-data="{templet:'#menuName'}">名称</th>
                    <th lay-data="{field:'parentName'}">上级菜单</th>
                    <th lay-data="{field:'route'}">路由</th>
                    <th lay-data="{field:'order', sort: true}">排序</th>
                    <th lay-data="{field:'statusText', sort: true}">状态</th>
                    <th lay-data="{field:'created_at'}">添加时间</th>
                    <th lay-data="{field:'updated_at'}">更新时间</th>
                    <th lay-data="{width:200, templet:'#action'}">操作</th>
                </tr>
                </thead>
            </table>
            <div>
                <form class="layui-form" method="post" action="{{ route('admin::menu.batch') }}">
                    <div class="layui-inline">
                        <label class="layui-form-label">操作类型</label>
                        <div class="layui-input-inline">
                            <select name="type">
                                <option value="disable">禁用</option>
                                <option value="enable">启用</option>
                                <option value="parent">设置父级菜单</option>
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="params" value="" placeholder="操作相关参数" class="layui-input">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-list" lay-filter="form-batch" id="batchBtn" lay-submit>
                                执行批量操作
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script type="text/html" id="menuName">
    <a href="?pid=<% d.id %>" class="layui-table-link"><% d.name %></a>
</script>
<script type="text/html" id="action">
    <a href="<% d.editUrl %>" class="layui-table-link"><i class="layui-icon layui-icon-edit"></i></a>
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
        $('#discovery').click(function () {
            $.ajax({
                url: '{{ route("admin::menu.discovery") }}',
                data: {},
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
        });

        var form = layui.form,
            table = layui.table;
        form.on('submit(form-batch)', function(data){
            var checkStatus = table.checkStatus('test'),
                ids = [];

            if (checkStatus.data.length === 0) {
                layer.msg('未选中待操作的行数据');
                return false;
            }
            checkStatus.data.forEach(function (item) {
                ids.push(item.id);
            });
            data.field.ids = ids;

            window.form_submit = $('#batchBtn');
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
                            location.href = result.redirect;
                        }
                    });
                }
            });

            return false;
        });
    </script>
@endsection