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
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected $formNames = ['id', 'name', 'password', 'status'];

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
        return view('admin.adminUser.index', ['title' => '管理员列表', 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 管理员列表数据
     *
     * @package Request $request
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $data = AdminUserRepository::list($perPage);

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
     */
    public function save(AdminUserRequest $request)
    {
        AdminUserRepository::add($request->only($this->formNames));

        return [
            'code' => 0,
            'msg' => '新增成功',
            'redirect' => route('admin::adminUser.index')
        ];
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

        AdminUserRepository::update($id, $data);

        return [
            'code' => 0,
            'msg' => '编辑成功',
            'redirect' => route('admin::adminUser.index')
        ];
    }
}
