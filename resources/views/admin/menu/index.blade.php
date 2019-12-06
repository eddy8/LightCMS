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
                    <th lay-data="{field: 'group'}">分组</th>
                    <th lay-data="{field:'parentName'}">上级菜单</th>
                    <th lay-data="{field:'route'}">路由</th>
                    <th lay-data="{field:'url'}">URL</th>
                    <th lay-data="{field:'order', sort: true, edit: true}">排序</th>
                    <th lay-data="{field:'status', sort: true, templet: '#statusTemplet', event: 'statusEvent'}">显示</th>
                    <th lay-data="{field:'created_at'}">添加时间</th>
                    <th lay-data="{field:'updated_at'}">更新时间</th>
                    <th lay-data="{width:100, templet:'#action'}">操作</th>
                </tr>
                </thead>
            </table>
            <div>
                <form class="layui-form" method="post" action="{{ route('admin::menu.batch') }}">
                    <div class="layui-inline">
                        <label class="layui-form-label">操作类型</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-filter="action-type">
                                <option value="disable">禁用</option>
                                <option value="enable">启用</option>
                                <option value="lock_name">锁定名称</option>
                                <option value="parent">设置父级菜单</option>
                                <option value="order">设置排序</option>
                                <option value="group">设置分组</option>
                                <option value="delete">删除</option>
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
    <a href="<% d.editUrl %>" class="layui-table-link" title="编辑"><i class="layui-icon layui-icon-edit"></i></a>
    <a href="javascript:;" class="layui-table-link" title="删除" style="margin-left: 10px" onclick="deleteMenu('<% d.deleteUrl %>')"><i class="layui-icon layui-icon-delete"></i></a>
</script>
<script type="text/html" id="statusTemplet">
    <input type="checkbox" name="status" lay-skin="switch" lay-text="是|否"
    <%# if (d.status == 1) { %>
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

        var table = layui.table;
        table.on('edit(test)', function(obj){ //注：edit是固定事件名，test是table原始容器的属性 lay-filter="对应的值"
            $.ajax({
                url: '{{ route('admin::menu.batch') }}',
                method: 'post',
                dataType: 'json',
                data: {params: obj.value, ids: [obj.data.id], 'type': 'order'},
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
                statusEvent: "status",
            };

            var key = maps[event];
            var val = tr.find("input[name='" + key + "']").prop('checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('admin::menu.batch') }}',
                method: 'post',
                dataType: 'json',
                data: {ids: [obj.data.id], 'type': val === 1 ? 'enable' : 'disable'},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 3});
                        return false;
                    }
                    layer.msg(result.msg, {icon: 1});
                    location.reload();
                }
            });
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
                            location.href = '{!! url()->previous() !!}';
                        }
                    });
                }
            });
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
                                location.href = '{!! url()->previous() !!}';
                            }
                        });
                    }
                });

                layer.close(index);
            });
        }

        var form = layui.form,
            table = layui.table;
        form.on('submit(form-batch)', function(data){
            if(!confirm('确定执行批量操作？')){
                return false;
            }
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
                            location.href = '{!! url()->previous() !!}';
                        }
                    });
                }
            });

            return false;
        });

        form.on('select(action-type)', function(data){
            if (data.value === 'parent') {
                $('input[name=params]').attr('placeholder', '请填写父级菜单的ID');
            } else if (data.value === 'order') {
                $('input[name=params]').attr('placeholder', '请填写排序值');
            } else if (data.value === 'group') {
                $('input[name=params]').attr('placeholder', '请填写分组名称');
            } else {
                $('input[name=params]').attr('placeholder', '');
            }
        });
    </script>
@endsection