<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Entity;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

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
            $item->editUrl = route('admin::entity.edit', ['id' => $item->id]);
            $item->deleteUrl = route('admin::entity.delete', ['id' => $item->id]);
            $item->fieldUrl = route('admin::entityField.index') . '?entity_id=' . $item->id;
            $item->contentUrl = route('admin::content.index', ['entity' => $item->id]);
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
        Entity::query()->create($data);
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
    }

    public static function update($id, $data)
    {
        return Entity::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Entity::query()->find($id);
    }

    public static function all()
    {
        return Entity::query()->get();
    }

    public static function systemMenu()
    {
        $entities = Entity::query()->pluck('name', 'id')->all();
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
