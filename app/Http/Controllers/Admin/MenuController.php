<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Model\Admin\Menu;
use App\Repository\Admin\MenuRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;
use Route;

class MenuController extends Controller
{
    protected $formNames = ['name', 'pid', 'status', 'order', 'route', 'group', 'remark'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '菜单管理', 'url' => route('admin::menu.index')];
    }

    /**
     * 菜单列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '菜单列表', 'url' => ''];
        return view('admin.menu.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 菜单列表数据
     *
     * @param Request $request
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $action = $request->get('action');
        $condition = $request->only($this->formNames);

        if (isset($condition['pid'])) {
            $condition['pid'] = ['=', $condition['pid']];
        } else {
            if ($action !== 'search') {
                $condition['pid'] = ['=', 0];
            }
        }

        $data = MenuRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 新增菜单
     *
     */
    public function create()
    {
        //dd(\App\Repository\Admin\MenuRepository::tree());
        $this->breadcrumb[] = ['title' => '新增菜单', 'url' => ''];
        return view('admin.menu.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 保存菜单
     *
     * @param MenuRequest $request
     * @return array
     */
    public function save(MenuRequest $request)
    {
        try {
            // 路由是否有效
            route($request->get('route'));
            MenuRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => route('admin::menu.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前菜单已存在' : '其它错误'),
                'redirect' => route('admin::menu.index')
            ];
        } catch (\InvalidArgumentException $e) {
            return [
                'code' => 2,
                'msg' => '新增失败：' . '无效路由',
                'redirect' => route('admin::menu.index')
            ];
        }
    }

    /**
     * 编辑菜单
     *
     * @param int $id
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑菜单', 'url' => ''];

        $model = MenuRepository::find($id);
        return view('admin.menu.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 更新菜单
     *
     * @param MenuRequest $request
     * @param int $id
     */
    public function update(MenuRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        if (!isset($data['status'])) {
            $data['status'] = Menu::STATUS_DISABLE;
        }

        try {
            MenuRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => route('admin::menu.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前菜单已存在' : '其它错误'),
                'redirect' => route('admin::menu.index')
            ];
        }
    }

    public function discovery()
    {
        $num = 0;

        foreach (Route::getRoutes()->getRoutesByName() as $k => $v) {
            if (Str::contains($k, 'admin::') && !MenuRepository::exist($k)) {
                if (in_array('GET', $v->methods) && !Str::contains($v->uri, '{')) {
                    $data = ['route' => $k, 'name' => $v->uri, 'status' => Menu::STATUS_ENABLE];
                } else {
                    $data = ['route' => $k, 'name' => $v->uri, 'status' => Menu::STATUS_DISABLE];
                }
                MenuRepository::add($data);
                $num++;
            }
        }

        return [
            'code' => 0,
            'msg' => '更新成功。新增菜单数：' . $num,
            'redirect' => route('admin::menu.index')
        ];
    }
}
