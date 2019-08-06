<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Entity;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CreateTableException;

class EntityRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        $data = Entity::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::entity.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::entity.delete', ['id' => $item->id]);
            $item->fieldUrl = route('admin::entityField.index') . '?entity_id=' . $item->id;
            $item->contentUrl = route('admin::content.index', ['entity' => $item->id]);
            $item->commentListUrl = route('admin::comment.index', ['entity_id' => $item->id]);
            return $item;
        });

        return [
            'code' => 0,
            'msg' => '',
            'count' => $data->total(),
            'data' => $data->items(),
        ];
    }

    /**
     * 新增模型
     */
    public static function add($data)
    {
        $entity = Entity::query()->create($data);
        try {
            $sql = <<<"EOF"
CREATE TABLE `{$data['table_name']}` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`created_at`  timestamp NULL DEFAULT NULL ,
`updated_at`  timestamp NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci
;
EOF;
            DB::statement($sql);
            return $entity;
        } catch (\Exception $e) {
            $entity->delete();
            throw new CreateTableException("创建数据库表异常");
        }
    }

    public static function update($id, $data)
    {
        return Entity::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Entity::query()->find($id);
    }

    public static function external($id)
    {
        return Entity::query()->External()->find($id);
    }

    public static function all()
    {
        return Entity::query()->get();
    }

    public static function systemMenu()
    {
        $entities = Entity::query()->where('is_show_content_manage', Entity::CONTENT_MANAGE_YES)
            ->pluck('name', 'id')->all();
        $autoMenu = [];
        foreach ($entities as $k => $v) {
            $autoMenu[] = [
                'url' => route('admin::content.index', ['entity' => $k]),
                'name' => $v,
            ];
        }

        return $autoMenu;
    }
}
