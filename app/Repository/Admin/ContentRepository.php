<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Content;
use App\Model\Admin\ContentTag;
use App\Model\Admin\Entity;
use App\Model\Admin\EntityField;
use App\Repository\Searchable;

/**
 * 使用当前类时必须先调用 setTable 方法设置所要操作的数据库表
 * @package App\Repository\Admin
 */
class ContentRepository
{
    use Searchable;

    /**
     * @var \App\Model\Admin\Model
     */
    protected static $model = null;

    public static function list($entity, $perPage, $condition = [])
    {
        $sortField = 'id';
        $sortType = 'desc';
        if (isset($condition['light_sort_fields'])) {
            $tmp = explode(',', $condition['light_sort_fields']);
            $sortField = isset($tmp[0]) && ($tmp[0] != '') ? $tmp[0] : $sortField;
            $sortType = isset($tmp[1]) && in_array($tmp[1], ['asc', 'desc'], true) ? $tmp[1] : $sortType;
            unset($condition['light_sort_fields']);
        }

        $data = self::$model->newQuery()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy($sortField, $sortType)
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

    public static function add($data, Entity $entity)
    {
        self::$model->setRawAttributes(self::processParams($data, $entity))->save();
        return self::$model;
    }

    public static function update($id, $data, Entity $entity)
    {
        return self::$model->newQuery()->where('id', $id)->update(self::processParams($data, $entity));
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

    public static function model()
    {
        return self::$model;
    }

    protected static function processParams($data, Entity $entity)
    {
        return collect($data)->map(function ($item, $key) use ($entity) {
            if (is_array($item)) {
                return implode(',', $item);
            } elseif ($item === '' || preg_match('/^\d+(,\d+)*/', $item)) {
                // select多选类型表单，数据类型为 unsignedInteger 的求和保存，查询时可以利用 AND 运算查找对应值
                $fieldType = EntityField::where('entity_id', $entity->id)
                    ->where('form_type', 'selectMulti')
                    ->where('name', $key)->value('type');
                if ($fieldType == 'unsignedInteger') {
                    return array_sum(explode(',', $item));
                }
                return $item;
            } else {
                return $item;
            }
        })->toArray();
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

    public static function tags($entityId, $contentId)
    {
        return ContentTag::query()->where('entity_id', $entityId)->where('content_id', $contentId)
            ->leftJoin('tags', 'tags.id', '=', 'content_tags.tag_id')
            ->get(['name', 'tag_id']);
    }

    public static function tagNames($entityId, $contentId)
    {
        return self::tags($entityId, $contentId)->implode('name', ',');
    }
}
