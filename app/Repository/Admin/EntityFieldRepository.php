<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\EntityField;
use App\Repository\Searchable;

class EntityFieldRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = EntityField::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            $item->editUrl = route('admin::entityField.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::entityField.delete', ['id' => $item->id]);
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
        return EntityField::query()->create($data);
    }

    public static function update($id, $data)
    {
        return EntityField::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return EntityField::query()->find($id);
    }

    public static function delete($id)
    {
        return EntityField::destroy($id);
    }
}
