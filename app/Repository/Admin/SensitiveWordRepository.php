<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\SensitiveWord;
use App\Repository\Searchable;

class SensitiveWordRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = SensitiveWord::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::SensitiveWord.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::SensitiveWord.delete', ['id' => $item->id]);
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
        return SensitiveWord::query()->create($data);
    }

    public static function update($id, $data)
    {
        return SensitiveWord::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return SensitiveWord::query()->find($id);
    }

    public static function delete($id)
    {
        return SensitiveWord::destroy($id);
    }

    public static function exist($condition)
    {
        return SensitiveWord::where($condition)->first();
    }
}
