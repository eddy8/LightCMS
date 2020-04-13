<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EntityRequest;
use App\Repository\Admin\EntityRepository;
use App\Model\Admin\Entity;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CreateTableException;

class EntityController extends Controller
{
    protected $formNames = [
        'name', 'table_name', 'description', 'is_internal', 'enable_comment', 'is_show_content_manage'
    ];

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
     * 模型管理-模型列表数据接口
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
            $createDB = $request->post('is_modify_db');
            EntityRepository::add($request->only($this->formNames), $createDB);
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (CreateTableException $e) {
            return [
                'code' => 2,
                'msg' => '新增失败：创建数据库表失败，数据表已存在或其它原因',
                'redirect' => false
            ];
        } catch (QueryException $e) {
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

    /**
     * 模型管理-删除模型
     *
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $password = $request->post('password');
        if (!$password) {
            return [
                'code' => 1,
                'msg' => '密码不能为空',
            ];
        }
        if (!Auth::guard('admin')->attempt(['id' => $request->user()->id, 'password' => $password])) {
            return [
                'code' => 2,
                'msg' => '密码错误',
            ];
        }
        EntityRepository::delete($id);
        return [
            'code' => 0,
            'msg' => '删除成功',
            'reload' => true
        ];
    }

    /**
     * 模型管理-复制模型
     *
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public function copy(Request $request, $id)
    {
        $this->validate($request, [
            'table_name' => ['required', 'max:64', 'regex:/^[0-9a-zA-Z$_]+$/'],
        ], [
            'table_name.required' => '表名称不能为空',
            'table_name.max' => '表名称长度不能超过64',
            'table_name.regex' => '表名称格式有误',
        ]);

        try {
            $tableName = $request->post('table_name');
            EntityRepository::copy($tableName, $id);
            return [
                'code' => 0,
                'msg' => '复制成功',
                'reload' => true
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 5,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * 模型管理-添加默认菜单
     *
     * @param integer $id
     * @return array
     */
    public function menu($id)
    {
        try {
            $entity = Entity::findOrFail($id);
            EntityRepository::addDefaultMenus($entity);
            return [
                'code' => 0,
                'msg' => '添加成功',
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 5,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
