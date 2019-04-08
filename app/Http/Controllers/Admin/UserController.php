<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Model\Admin\User;
use App\Repository\Admin\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $formNames = ['phone', 'status'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '会员列表', 'url' => route('admin::user.index')];
    }

    /**
     * 会员管理-会员列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '会员列表', 'url' => ''];
        return view('admin.user.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 会员列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $condition = $request->only($this->formNames);

        $data = UserRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 会员管理-新增会员
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增会员', 'url' => ''];
        return view('admin.user.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 会员管理-保存会员
     *
     * @param UserRequest $request
     * @return array
     */
    public function save(UserRequest $request)
    {
        try {
            array_push($this->formNames, 'password');
            UserRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前会员已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 会员管理-编辑会员
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑会员', 'url' => ''];

        $model = UserRepository::find($id);
        return view('admin.user.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 会员管理-更新会员
     *
     * @param UserRequest $request
     * @param int $id
     * @return array
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        if (!isset($data['status'])) {
            $data['status'] = User::STATUS_DISABLE;
        }
        try {
            UserRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前会员已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 会员管理-删除会员
     *
     * @param int $id
     */
    public function delete($id)
    {
        try {
            UserRepository::delete($id);
            return [
                'code' => 0,
                'msg' => '删除成功',
                'redirect' => route('admin::user.index')
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 1,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => false
            ];
        }
    }

}
