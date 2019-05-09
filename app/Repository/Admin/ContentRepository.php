<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Content;
use App\Repository\Searchable;

class ContentRepository
{
    use Searchable;

    /**
     * @var \App\Model\Admin\Model
     */
    protected static $model = null;

    public static function list($entity, $perPage, $condition = [])
    {
        $data = self::$model->newQuery()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) use ($entity) {
            xssFilter($item);
            $item->editUrl = route('admin::content.edit', ['id' => $item->id, 'entity' => $entity]);
            $item->deleteUrl = route('admin::content.delete', ['id' => $item->id, 'entity' => $entity]);
            $item->commentListUrl = route('admin::comment.index', ['content_id' => $item->id, 'entity_id' => $entity]);
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
        return self::$model->setRawAttributes(self::processParams($data))->save();
    }

    public static function update($id, $data)
    {
        return self::$model->newQuery()->where('id', $id)->update(self::processParams($data));
    }

    public static function find($id)
    {
        return self::$model->newQuery()->find($id);
    }

    public static function findOrFail($id)
    {
        return self::$model->newQuery()->findOrFail($id);
    }

    public static function delete($id)
    {
        return self::$model->newQuery()->where('id', $id)->delete();
    }

    public static function setTable($table)
    {
        self::$model = new Content();
        return self::$model->setTable($table);
    }

    protected static function processParams($data)
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return implode(',', $item);
            } else {
                return $item;
            }
        }, $data);
    }

    public static function adjacent($id)
    {
        return [
            'previous' => self::$model->newQuery()->where('id', '<', $id)->first(),
            'next' => self::$model->newQuery()->where('id', '>', $id)->first()
        ];
    }

    public static function paginate($perPage = 10)
    {
        return self::$model->newQuery()
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
