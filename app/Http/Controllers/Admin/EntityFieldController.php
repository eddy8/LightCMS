<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EntityFieldRequest;
use App\Repository\Admin\EntityFieldRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EntityFieldController extends Controller
{
    protected $formNames = [];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '模型字段列表', 'url' => route('admin::entityField.index')];
    }

    /**
     * 模型字段管理-模型字段列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '模型字段列表', 'url' => ''];
        return view('admin.entityField.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型字段列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $condition = $request->only($this->formNames);

        $data = EntityFieldRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 模型字段管理-新增模型字段
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增模型字段', 'url' => ''];
        return view('admin.entityField.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型字段管理-保存模型字段
     *
     * @param EntityFieldRequest $request
     * @return array
     */
    public function save(EntityFieldRequest $request)
    {
        try {
            EntityFieldRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => route('admin::entityField.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前模型字段已存在' : '其它错误'),
                'redirect' => route('admin::entityField.index')
            ];
        }
    }

    /**
     * 模型字段管理-编辑模型字段
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑模型字段', 'url' => ''];

        $model = EntityFieldRepository::find($id);
        return view('admin.entityField.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型字段管理-更新模型字段
     *
     * @param EntityFieldRequest $request
     * @param int $id
     * @return array
     */
    public function update(EntityFieldRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            EntityFieldRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => route('admin::entityField.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前模型字段已存在' : '其它错误'),
                'redirect' => route('admin::entityField.index')
            ];
        }
    }

    /**
     * 模型字段管理-删除模型字段
     *
     * @param int $id
     */
    public function delete($id)
    {
        try {
            MenuRepository::delete($id);
            return [
                'code' => 0,
                'msg' => '删除成功',
                'redirect' => route('admin::menu.index')
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 1,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => route('admin::menu.index')
            ];
        }
    }

}
