<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\AdminUser;

class AdminUserRepository
{
    public static function list($perPage)
    {
        $data = AdminUser::query()->paginate($perPage);
        $data->transform(function ($item) {
            $item->editUrl = route('admin::adminUser.edit', ['id' => $item->id]);
            $item->statusText = $item->status == AdminUser::STATUS_ENABLE ? '启用' : '禁用';
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
}