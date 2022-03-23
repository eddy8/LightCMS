<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Repository\Admin;

use App\Model\Admin\Category;
use App\Repository\Searchable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{
    use Searchable;

    public static function listSingle($perPage, $condition = [])
    {
        $data = Category::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admin::category.edit', ['id' => $item->id]);
            $item->parentName = $item->pid == 0 ? '顶级菜单' : $item->parent->name;
            $item->entityName = $item->entity ? $item->entity->name : '';
            unset($item->entity);
            return $item;
        });

        return [
            'code' => 0,
            'msg' => '',
            'count' => $data->total(),
            'data' => $data->items(),
        ];
    }

    public static function list($perPage, $condition = [])
    {
        $list = [];
        $data = Category::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->each(function ($item, $key) use (&$list) {
            xssFilter($item);
            $item->editUrl = route('admin::category.edit', ['id' => $item->id]);
            $item->parentName = $item->pid == 0 ? '顶级菜单' : $item->parent->name;
            $item->entityName = $item->entity ? $item->entity->name : '';
            unset($item->entity);

            array_push($list, $item);

            $item->children->each(function ($v, $k) use (&$list) {
                $v->editUrl = route('admin::category.edit', ['id' => $v->id]);
                //$v->parentName = $v->pid == 0 ? '顶级菜单' : $v->parent->name;
                //$v->entityName = $v->entity ? $v->entity->name : '';
                $v->name = '|--------' . $v->name;
                array_push($list, $v);
            });
            unset($item->children);
        });

        return [
            'code' => 0,
            'msg' => '',
            'count' => count($list),
            'data' => $list,
        ];
    }

    public static function add($data)
    {
        return Category::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Category::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Category::query()->find($id);
    }

    public static function tree($entity_id = null, $pid = 0, $all = null, $level = 0, $path = [])
    {
        if (is_null($all)) {
            if (is_null($entity_id)) {
                $all = Category::query()->get();
            } else {
                $all = Category::query()->where('model_id', $entity_id)->get();
            }
        }
        return $all->where('pid', $pid)
            ->map(function (Category $model) use ($all, $level, $path, $entity_id) {
                $data = [
                    'id' => $model->id,
                    'name' => $model->name,
                    'level' => $level,
                    'pid' => $model->pid,
                    'path' => $path,
                    'order' => $model->order,
                    'all' => $model->toArray(),
                ];

                $child = $all->where('pid', $model->id);
                if ($child->isEmpty()) {
                    return $data;
                }

                array_push($path, $model->id);
                $data['children'] = self::tree($entity_id, $model->id, $all, $level + 1, $path);
                return $data;
            })->sortBy('order');
    }

    /**
     * 获取分类数组，key为分类ID，value为分类名称
     *
     * @return array
     */
    public static function idMapNameArr(int $entityId = 0): array
    {
        return Category::query()->when($entityId > 0, function ($query) use ($entityId) {
            return $query->where('model_id', $entityId);
        })->pluck('name', 'id')->toArray();
    }

    /**
     * 获取分类表单（select）配置
     *
     * @param string $title 展示标题
     * @return array
     */
    public static function selectForm(string $title = '分类'): array
    {
        return [ // key 为字段名称，value 为相关配置
            'showType' => 'select',
            'searchType' => '=',
            'title' => $title,
            'enums' => self::idMapNameArr(),
        ];
    }

    public static function cacheTree()
    {
        return Cache::rememberForever('category:tree', function () {
            return self::tree();
        });
    }

    /**
     * 获取指定层级的所有分类，根分类层级为 0
     *
     * @param int $level
     * @param null $tree
     * @return Collection
     */
    public static function levelCategories(int $level = 0, $tree = null): Collection
    {
        if (is_null($tree)) {
            $tree = self::cacheTree();
        }
        $data = new Collection();
        foreach ($tree as $v) {
            if ($v['level'] === $level) {
                $data->push($v);
            }
            if ($v['level'] < $level && isset($v['children'])) {
                $result = self::levelCategories($level, $v['children']);
                foreach ($result as $vv) {
                    $data->push($vv);
                }
            }
        }
        return $data;
    }

    /**
     * 获取指定分类的所有叶子节点分类，$categoryId 为 0 时获取所有叶子节点分类
     *
     * @param int $categoryId
     * @param null $tree
     * @return Collection
     */
    public static function leafCategories(int $categoryId = 0, $tree = null): Collection
    {
        if (is_null($tree)) {
            $tree = self::cacheTree();
        }
        $data = new Collection();
        foreach ($tree as $v) {
            if ($categoryId > 0 && $v['id'] === $categoryId) {
                if (!isset($v['children'])) {
                    return $data;
                }
                return self::leafCategories($categoryId, $v['children']);
            }
            if (isset($v['children'])) {
                $result = self::leafCategories($categoryId, $v['children']);
                foreach ($result as $vv) {
                    $data->push($vv);
                }
            } else {
                if ($categoryId === 0 || ($categoryId > 0 && in_array($categoryId, $v['path']))) {
                    $data->push($v);
                }
            }
        }
        return $data;
    }

    /**
     * 获取指定分类的所有父级分类，没有父分类时返回空数组
     *
     * @param int $categoryId
     * @param null $tree
     * @return array
     */
    public static function parentCategories(int $categoryId, $tree = null): array
    {
        if (is_null($tree)) {
            $tree = self::cacheTree();
        }

        foreach ($tree as $v) {
            if ($v['id'] === $categoryId) {
                return $v['path'];
            }
            if (isset($v['children'])) {
                $result = self::parentCategories($categoryId, $v['children']);
                if (!empty($result)) {
                    return $result;
                }
            }
        }

        return [];
    }
}
