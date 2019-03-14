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
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        $data->transform(function ($item) {
            $item->editUrl = route('admin::entityField.edit', ['id' => $item->id]);
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

    public static function doctrineConn()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $db = config('database.connections')[config('database.default')];
        $connectionParams = [
            'dbname' => $db['database'],
            'user' => $db['username'],
            'password' => $db['password'],
            'host' => $db['host'],
            'driver' => 'pdo_mysql',
        ];
        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }

    public static function tableFieldExist($table, $field)
    {
        $conn = self::doctrineConn();
        $sm = $conn->getSchemaManager();

        return array_key_exists($field, $sm->listTableColumns($table));
    }

    public static function tableAddColumn($table, $field)
    {
        $conn = self::doctrineConn();
        $sm = $conn->getSchemaManager();

        return key_exists($field, $sm->listTableColumns($table));
    }
}
