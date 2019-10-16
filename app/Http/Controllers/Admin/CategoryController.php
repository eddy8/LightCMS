<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Repository\Admin\CategoryRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected $formNames = ['name', 'pid', 'order', 'title', 'keywords', 'description', 'model_id'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '分类列表', 'url' => route('admin::category.index')];
    }

    /**
     * 分类管理-分类列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '分类列表', 'url' => ''];
        return view('admin.category.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 分类管理-分类列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $action = $request->get('action');
        $this->formNames[] = 'created_at';
        $condition = $request->only($this->formNames);

        if (isset($condition['pid'])) {
            $condition['pid'] = ['=', $condition['pid']];
        } else {
            if ($action !== 'search') {
                $condition['pid'] = ['=', 0];
            }
        }

        $data = CategoryRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 分类管理-新增分类
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增分类', 'url' => ''];
        return view('admin.category.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 分类管理-保存分类
     *
     * @param CategoryRequest $request
     * @return array
     */
    public function save(CategoryRequest $request)
    {
        try {
            CategoryRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前分类已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 分类管理-编辑分类
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑分类', 'url' => ''];

        $model = CategoryRepository::find($id);
        return view('admin.category.add', [
            'id' => $id,
            'model' => $model,
            'breadcrumb' => $this->breadcrumb,
            'disabledCategoryIds' => [$id],
            'disabledChildren' => true,
        ]);
    }

    /**
     * 分类管理-更新分类
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return array
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            CategoryRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前分类已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }
}
