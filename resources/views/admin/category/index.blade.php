@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-form layui-card-header light-search">
            <form>
                <input type="hidden" name="action" value="search">
            @include('admin.searchField', ['data' => App\Model\Admin\Category::$searchField])
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
            <table class="layui-table" lay-data="{url:'{{ route('admin::category.list') }}?{{ request()->getQueryString() }}', page:true, limit:50, id:'test', toolbar:'<div><a href=\'{{ route('admin::category.create') }}\'><i class=\'layui-icon layui-icon-add-1\'></i>新增分类</a><div class=\'layui-input-inline\'><input type=\'text\' name=\'word\' class=\'layui-input\' id=\'word\' placeholder=\'当前表格内搜索\'></div></div>'}" lay-filter="test">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                    <th lay-data="{templet:'#categoryName'}">名称</th>
                    @include('admin.listHead', ['data' => App\Model\Admin\Category::$listField])
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
</script>
<script type="text/html" id="categoryName">
    <a href="?pid=<% d.id %>" class="layui-table-link"><% d.name %></a>
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

        $('#word').keyup(function () {
            $('.layui-table tr').each(function(i, v){
                $(v).css({"background-color": ""});
            });

            var word = $.trim($(this).val()).toLowerCase();
            if (word.length === 0) {
                return;
            }
            $('.layui-table tr').each(function(i, v){
                $(v).find('td').each(function(ii, vv) {
                    if ($(vv).text().toLowerCase().indexOf(word) >= 0) {
                        $(v).css({"background-color": "yellow"});
                        return false;
                    }
                });
            });
        });


    </script>
@endsection
