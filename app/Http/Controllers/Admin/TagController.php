<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Repository\Admin\TagRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Model\Admin\ContentTag;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    protected $formNames = ['name', 'created_at'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '标签列表', 'url' => route('admin::tag.index')];
    }

    /**
     * 标签管理-标签列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '标签列表', 'url' => ''];
        return view('admin.tag.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 标签管理-标签列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $condition = $request->only($this->formNames);

        $data = TagRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 标签管理-新增标签
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增标签', 'url' => ''];
        return view('admin.tag.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 标签管理-保存标签
     *
     * @param TagRequest $request
     * @return array
     */
    public function save(TagRequest $request)
    {
        try {
            TagRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前标签已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 标签管理-编辑标签
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑标签', 'url' => ''];

        $model = TagRepository::find($id);
        return view('admin.tag.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 标签管理-更新标签
     *
     * @param TagRequest $request
     * @param int $id
     * @return array
     */
    public function update(TagRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            TagRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前标签已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 标签管理-删除标签
     *
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();

            TagRepository::delete($id);
            ContentTag::where('tag_id', $id)->delete();

            DB::commit();
            return [
                'code' => 0,
                'msg' => '删除成功',
                'redirect' => route('admin::tag.index')
            ];
        } catch (\RuntimeException $e) {
            DB::rollBack();
            return [
                'code' => 1,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => false
            ];
        }
    }
}
