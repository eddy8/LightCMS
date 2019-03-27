<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Template;
use App\Repository\Searchable;

class TemplateRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Template::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::template.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::template.delete', ['id' => $item->id]);
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
        return Template::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Template::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Template::query()->find($id);
    }

    public static function delete($id)
    {
        return Template::destroy($id);
    }
}
