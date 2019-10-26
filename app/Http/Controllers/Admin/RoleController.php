<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Repository\Admin\MenuRepository;
use App\Repository\Admin\RoleRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    protected $formNames = ['name'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '角色列表', 'url' => route('admin::role.index')];
    }

    /**
     * 角色管理-角色列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '角色列表', 'url' => ''];
        return view('admin.role.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 角色管理-角色列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $this->formNames[] = 'created_at';
        $condition = $request->only($this->formNames);

        $data = RoleRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 角色管理-新增角色
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增角色', 'url' => ''];
        return view('admin.role.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 角色管理-保存角色
     *
     * @param RoleRequest $request
     * @return array
     */
    public function save(RoleRequest $request)
    {
        try {
            $data = $request->only($this->formNames);
            $data['guard_name'] = 'admin';
            RoleRepository::add($data);
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前角色已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 角色管理-编辑角色
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑角色', 'url' => ''];

        $model = RoleRepository::find($id);
        return view('admin.role.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 角色管理-更新角色
     *
     * @param RoleRequest $request
     * @param int $id
     * @return array
     */
    public function update(RoleRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            RoleRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前角色已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 角色管理-分配权限
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function permission($id)
    {
        $this->breadcrumb[] = ['title' => '分配权限', 'url' => ''];
        $role = RoleRepository::find($id);
        return view('admin.role.permission', [
            'id' => $id,
            'breadcrumb' => $this->breadcrumb,
            'rolePermissions' => $role->permissions,
            'role' => $role,
        ]);
    }

    /**
     * 角色管理-更新权限
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function updatePermission(Request $request, $id)
    {
        $role = RoleRepository::find($id);
        $permissions = $request->input('permission');
        $role->syncPermissions(MenuRepository::get(array_keys($permissions)));
        return [
            'code' => 0,
            'msg' => '操作成功',
            'redirect' => true
        ];
    }
}
