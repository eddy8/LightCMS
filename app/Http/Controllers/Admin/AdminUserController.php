<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Model\Admin\AdminUser;
use App\Repository\Admin\AdminUserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    protected $formNames = ['name', 'password', 'status'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '管理员管理', 'url' => route('admin::adminUser.index')];
    }

    /**
     * 管理员列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '管理员列表', 'url' => ''];
        return view('admin.adminUser.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 管理员列表数据
     *
     * @param Request $request
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $condition = $request->only($this->formNames);
        $data = AdminUserRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 新增管理员用户
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增管理员', 'url' => ''];
        return view('admin.adminUser.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 保存管理员用户
     *
     * @param AdminUserRequest $request
     * @return array
     */
    public function save(AdminUserRequest $request)
    {
        try {
            AdminUserRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => route('admin::adminUser.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前用户已存在' : '其它错误'),
                'redirect' => route('admin::adminUser.index')
            ];
        }
    }

    /**
     * 编辑管理员用户
     *
     * @param int $id
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑管理员', 'url' => ''];

        $user = AdminUserRepository::find($id);
        return view('admin.adminUser.add', ['id' => $id, 'user' => $user, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 更新管理员用户
     *
     * @param AdminUserRequest $request
     * @param int $id
     */
    public function update(AdminUserRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        if (!isset($data['status'])) {
            $data['status'] = AdminUser::STATUS_DISABLE;
        }
        if ($request->input('password') == '') {
            unset($data['password']);
        }

        try {
            AdminUserRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => route('admin::adminUser.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前用户已存在' : '其它错误'),
                'redirect' => route('admin::adminUser.index')
            ];
        }
    }
}
