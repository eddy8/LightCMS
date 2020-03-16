<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Tag;
use App\Repository\Searchable;

class TagRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Tag::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::tag.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::tag.delete', ['id' => $item->id]);
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
        return Tag::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Tag::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Tag::query()->find($id);
    }

    public static function delete($id)
    {
        return Tag::destroy($id);
    }
}
