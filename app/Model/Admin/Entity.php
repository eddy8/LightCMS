<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Entity extends Model
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    protected $guarded = [];

    public static $listField = [
        'name' => '名称',
        'table_name' => '数据库表名',
        'description' => '描述',
    ];
}
