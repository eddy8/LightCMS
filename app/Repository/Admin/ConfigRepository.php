<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Config;
use App\Repository\Searchable;
use Illuminate\Support\Facades\Cache;

class ConfigRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Config::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::config.edit', ['id' => $item->id]);
            $item->type = Config::$types[$item->type];
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
        return Config::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Config::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Config::query()->find($id);
    }

    public static function all()
    {
        return Cache::rememberForever(config('light.cache_key.config'), function () {
            $value = Config::query()->select(['key', 'value'])->get();
            if ($value->isEmpty()) {
                return [];
            }

            return $value->mapWithKeys(function ($item) {
                return [$item->key => $item->value];
            })->all();
        });
    }

    public static function groupNames()
    {
        return Config::query()->select('group')->distinct()->pluck('group')->toArray();
    }
}
