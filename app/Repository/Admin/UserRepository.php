<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\User;
use App\Repository\Searchable;

class UserRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = User::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::user.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::user.delete', ['id' => $item->id]);
            $item->statusText = $item->status == User::STATUS_ENABLE ?
                '<span class="layui-badge layui-bg-green">启用</span>' :
                '<span class="layui-badge">禁用</span>';
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
        return User::query()->create($data);
    }

    public static function update($id, $data)
    {
        return User::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return User::query()->find($id);
    }

    public static function delete($id)
    {
        return User::destroy($id);
    }
}
