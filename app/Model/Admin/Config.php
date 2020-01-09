<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Config extends Model
{
    public static $searchField = [
        'name' => '名称',
        'key' => '标志符',
    ];

    public static $listField = [
        'group' => '分组',
        'name' => '名称',
        'key' => '标志符',
        'type' => '类型',
        'value' => '值',
    ];

    const TYPE_NUM = 0;
    const TYPE_STR = 1;
    const TYPE_JSON = 2;

    public static $types = [
        self::TYPE_NUM => '数值',
        self::TYPE_STR => '字符串',
        self::TYPE_JSON => 'JSON',
    ];

    protected $guarded = [];
}
