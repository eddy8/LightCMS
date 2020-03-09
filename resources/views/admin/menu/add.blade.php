@extends('admin.base')

@section('content')
    <div class="layui-card">

        @include('admin.breadcrumb')

        <div class="layui-card-body">
            <form class="layui-form" action="@if(isset($id)){{ route('admin::menu.update', ['id' => $id]) }}@else{{ route('admin::menu.save') }}@endif" method="post">
                @if(isset($id)) {{ method_field('PUT') }} @endif
                <div class="layui-form-item">
                    <div class="layui-inline">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" required  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $model->name ?? ''  }}">
                    </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">锁定名称</label>
                        <div class="layui-input-inline">
                            <input type="checkbox" name="is_lock_name" lay-skin="switch" lay-text="锁定|不锁定" value="1" @if(isset($model) && $model->is_lock_name == App\Model\Admin\Menu::LOCK_NAME) checked @endif>
                        </div>
                        <div class="layui-form-mid layui-word-aux">锁定名称则菜单自动更新时不会更新当前菜单的名称和分组等信息</div>
                    </div>
                </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">上级菜单</label>
                        <div class="layui-input-block" style="width: 400px">
                            <select name="pid" lay-verify="required">
                                <option value="0">顶级菜单</option>
                                @foreach(App\Repository\Admin\MenuRepository::getTree() as $v)
                                    @include('admin.menu', $v)
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">路由</label>
                        <div class="layui-input-block">
                            <input type="text" name="route" required  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $model->route ?? ''  }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">路由参数</label>
                        <div class="layui-input-inline">
                            <input type="text" name="route_params" autocomplete="off" class="layui-input" value="{{ $model->route_params ?? ''  }}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">用于精确控制同一路由下不同路由参数值的访问权限。填写格式：参数名称:参数值</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">URL</label>
                        <div class="layui-input-block">
                            <input type="text" name="url" autocomplete="off" class="layui-input" value="{{ $model->url ?? ''  }}">
                        </div>
                    </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="text" name="order" autocomplete="off" class="layui-input" value="{{ $model->order ?? 1  }}">
                    </div>
                    <div class="layui-form-mid layui-word-aux">值越小排序越靠前</div>
                </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">分组</label>
                        <div class="layui-input-block">
                            <input type="text" name="group" autocomplete="off" class="layui-input" value="{{ $model->group ?? ''  }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-block">
                            <input type="text" name="remark" autocomplete="off" class="layui-input" value="{{ $model->remark ?? ''  }}">
                        </div>
                    </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="status" lay-skin="switch" lay-text="启用|禁用" value="1" @if(isset($model) && $model->status == App\Model\Admin\Menu::STATUS_ENABLE) checked @endif>
                    </div>
                </div>
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
    </script>
@endsection