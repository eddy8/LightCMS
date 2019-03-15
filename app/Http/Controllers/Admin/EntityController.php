<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EntityRequest;
use App\Repository\Admin\EntityRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class EntityController extends Controller
{
    protected $formNames = ['name', 'table_name', 'description'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '模型列表', 'url' => route('admin::entity.index')];
    }

    /**
     * 模型管理-模型列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '模型列表', 'url' => ''];
        return view('admin.entity.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $condition = $request->only($this->formNames);

        $data = EntityRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 模型管理-新增模型
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增模型', 'url' => ''];
        return view('admin.entity.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型管理-保存模型
     *
     * @param EntityRequest $request
     * @return array
     */
    public function save(EntityRequest $request)
    {
        try {
            EntityRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            Log::error($e);
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前模型已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 模型管理-编辑模型
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑模型', 'url' => ''];

        $model = EntityRepository::find($id);
        return view('admin.entity.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型管理-更新模型
     *
     * @param EntityRequest $request
     * @param int $id
     * @return array
     */
    public function update(EntityRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        unset($data['table_name']);
        try {
            EntityRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            Log::error($e);
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前模型已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

}
