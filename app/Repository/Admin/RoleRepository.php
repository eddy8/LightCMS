<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use Spatie\Permission\Models\Role;
use App\Repository\Searchable;

class RoleRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Role::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::role.edit', ['id' => $item->id]);
            $item->permissionUrl = route('admin::role.permission.edit', ['id' => $item->id]);
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
        return Role::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Role::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Role::query()->find($id);
    }

    public static function all()
    {
        return Role::all();
    }

    public static function exist($name)
    {
        return Role::query()->where('name', $name)->first();
    }
}
