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
            ->with('entity')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $formTypes = config('light.form_type');
        $data->transform(function ($item) use ($formTypes) {
            xssFilter($item);
            $item->entityName = $item->entity->name;
            $item->is_show_inline = $item->is_show_inline === EntityField::SHOW_INLINE ? '是' : '否';
            $item->is_show = $item->is_show === EntityField::SHOW_ENABLE ? '是' : '否';
            $item->form_type = $formTypes[$item->form_type];
            $item->editUrl = route('admin::entityField.edit', ['id' => $item->id]) . '?entity_id=' . $item->entity->id;
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

    public static function getByEntityId($id)
    {
        return  EntityField::query()->where('entity_id', $id)
            ->orderBy('order')->orderBy('is_show_inline')->get();
    }
}
