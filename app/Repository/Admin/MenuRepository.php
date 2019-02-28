<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Menu;
use App\Repository\Searchable;

class MenuRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Menu::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->with('parent')
            ->paginate($perPage);
        $data->transform(function ($item) {
            $item->editUrl = route('admin::menu.edit', ['id' => $item->id]);
            $item->statusText = $item->status == Menu::STATUS_ENABLE ?
                        '<span class="layui-badge layui-bg-green">启用</span>' :
                        '<span class="layui-badge">禁用</span>';
            $item->parentName = $item->pid == 0 ? '顶级菜单' : $item->parent->name;
            return $item;
        });

        return [
            'code' => 0,
            'msg' => '',
            'count' => $data->total(),
            'data' => $data->items(),
        ];
    }

    public static function add($data)
    {
        return Menu::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Menu::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Menu::query()->find($id);
    }

    public static function exist($route)
    {
        return Menu::query()->where('route', $route)->first();
    }

    public static function tree($pid = 0, $allMenus = null, $level = 0, $path = [])
    {
        if (is_null($allMenus)) {
            $allMenus = Menu::select('id', 'pid', 'name', 'route', 'group', 'status')->get();
        }
        return $allMenus->where('pid', $pid)
            ->map(function (Menu $menu) use ($allMenus, $level, $path) {
                $data = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'level' => $level,
                    'pid' => $menu->pid,
                    'path' => $path,
                    'route' => $menu->route,
                    'group' => $menu->group,
                    'status' => $menu->status,
                ];

                $child = $allMenus->where('pid', $menu->id);
                if ($child->isEmpty()) {
                    return $data;
                }

                array_push($path, $menu->id);
                $data['children'] = self::tree($menu->id, $allMenus, $level + 1, $path);
                return $data;
            });
    }

    public static function allRoot()
    {
        return Menu::query()->where('pid', 0)->where('status', 1)->get();
    }

    /**
     * 根据指定路由名获取根菜单
     *
     * @param string $route
     * @param null|array $tree
     * @throws \RuntimeException
     * @return array|null
     */
    public static function root($route, $tree = null)
    {
        if (is_null($tree)) {
            $tree = self::tree();
        }

        foreach ($tree as $menu) {
            if ($menu['route'] === $route) {
                if (empty($menu['path'])) {
                    return $menu;
                }

                $rootId = current($menu['path']);
                foreach (self::tree() as $v) {
                    if ($v['id'] === $rootId) {
                        return $v;
                    }
                }
            }

            if (isset($menu['children'])) {
                $m = self::root($route, $menu['children']);
                if (!is_null($m)) {
                    return $m;
                }
            }
        }

        return null;
    }
}