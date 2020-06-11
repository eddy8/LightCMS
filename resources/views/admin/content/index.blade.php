@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-form layui-card-header light-search" style="height: auto">
            <form>
                <input type="hidden" name="action" value="search">
            @include('admin.searchField', ['data' => App\Model\Admin\Content::$searchField])
            <div class="layui-inline">
                <label class="layui-form-label">创建日期</label>
                <div class="layui-input-inline">
                    <input type="text" name="created_at" class="layui-input" id="created_at" value="{{ request()->get('created_at') }}">
                </div>
            </div>
            @if(!empty(App\Model\Admin\Content::$sortFields))
            <div class="layui-inline">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <select name="light_sort_fields">
                        <option value="" @if(!request()->has('light_sort_fields')) selected @endif>请选择</option>
                        @foreach(App\Model\Admin\Content::$sortFields as $ik => $iv)
                            <option value="{{ $ik }}" @if(request()->has('light_sort_fields') && request()->get('light_sort_fields') !== "" && request()->get('light_sort_fields') == $ik) selected @endif>{{ $iv }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="layui-inline">
                <button class="layui-btn layuiadmin-btn-list" lay-filter="form-search" id="submitBtn">
                    <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                </button>
            </div>
            </form>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'{{ route('admin::content.list', ['entity' => $entity]) }}?{{ request()->getQueryString() }}', page:true, limit:50, id:'test', toolbar:'<div><a href=\'{{ route('admin::content.create', ['entity' => $entity]) }}\'><i class=\'layui-icon layui-icon-add-1\'></i><span class=\'layui-badge\'>新增{{ $entityModel->name }}内容</span></a></div>'}" lay-filter="test">
                <thead>
                <tr>
                    <th lay-data="{width:50, type:'checkbox'}"></th>
                    <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                    @include('admin.listHead', ['data' => App\Model\Admin\Content::$listField])
                    <th lay-data="{field:'created_at'}">添加时间</th>
                    <th lay-data="{field:'updated_at'}">更新时间</th>
                    <th lay-data="{width:200, templet:'#action'}">操作</th>
                </tr>
                </thead>
            </table>
            <div>
                <form class="layui-form" method="post" action="{{ route('admin::content.batch', ['entity' => $entity]) }}">
                    <div class="layui-inline">
                        <label class="layui-form-label">操作类型</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-filter="action-type">
                                <option value="delete">删除</option>
                            </select>
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
<script type="text/html" id="action">
    <a href="<% d.editUrl %>" class="layui-table-link" title="编辑"><i class="layui-icon layui-icon-edit"></i></a>
    <a href="javascript:;" class="layui-table-link" title="删除" style="margin-left: 10px" onclick="deleteMenu('<% d.deleteUrl %>')"><i class="layui-icon layui-icon-delete"></i></a>
    <a href="<% d.commentListUrl %>" class="layui-table-link" title="评论列表" style="margin-left: 10px"><i class="layui-icon layui-icon-reply-fill"></i></a>
    @foreach(App\Model\Admin\Content::$actionField as $k => $v)
    <a href="<% d.{{$k}} %>" class="layui-table-link" title="{{ $v['description'] }}" style="margin-left: 5px">{{ $v['title'] }}</a>
    @endforeach
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
    </script>
@endsection
