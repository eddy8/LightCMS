@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-form layui-card-header light-search">
            <form>
                <input type="hidden" name="action" value="search">
            <div class="layui-inline">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" autocomplete="off" class="layui-input" value="{{ request()->get('name') }}">
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
            <table class="layui-table" lay-data="{url:'{{ route('admin::menu.list') }}?{{ request()->getQueryString() }}', page:true, id:'test', toolbar:'<div><a href=\'{{ route('admin::menu.create') }}\'><i class=\'layui-icon layui-icon-add-1\'></i>新增菜单</a></div>'}" lay-filter="test">
                <thead>
                <tr>
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
    </script>
@endsection