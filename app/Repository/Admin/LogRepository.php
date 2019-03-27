<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Log;
use App\Repository\Searchable;

class LogRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Log::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            // 因列表展示用的layui table组件未进行xss处理，故在后端进行xss处理
            xssFilter($item);
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
        return Log::query()->create($data);
    }

    public static function find($id)
    {
        return Log::query()->find($id);
    }
}
