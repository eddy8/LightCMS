<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\Menu;
use App\Repository\Searchable;

class AdminUserRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = AdminUser::query()
            ->select('id', 'name', 'created_at', 'updated_at', 'status')
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->with('roles')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::adminUser.edit', ['id' => $item->id]);
            $item->roleUrl = route('admin::adminUser.role.edit', ['id' => $item->id]);
            $item->statusText = $item->status == AdminUser::STATUS_ENABLE ?
                    '<span class="layui-badge layui-bg-green">启用</span>' :
                    '<span class="layui-badge">禁用</span>';
            $item->roleNames = xssFilter($item->getRoleNames()->join(','));
            unset($item->roles);
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
        $data['password'] = bcrypt($data['password']);
        return AdminUser::query()->create($data);
    }

    public static function update($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return AdminUser::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return AdminUser::query()->find($id);
    }

    public static function roles(AdminUser $user)
    {
        return $user->roles();
    }

    public static function setDefaultPermission(AdminUser $user)
    {
        $logoutPermission = Menu::query()->where('route', 'admin::logout')->first();
        if ($logoutPermission) {
            $user->givePermissionTo($logoutPermission->name);
        }
    }
}
