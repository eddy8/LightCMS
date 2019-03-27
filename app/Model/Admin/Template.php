<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Template extends Model
{
    protected $guarded = [];

    public static $searchField = [
        'name' => '名称'
    ];

    public static $listField = [
        'name' => '名称',
        'group' => '分组'
    ];
}
