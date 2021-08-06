<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Log extends Model
{
    protected $guarded = [];

    public static $searchField = [
        'user_name' => '用户',
        'url' => 'URL',
        'data' => 'data',
        'route_name' => '路由名称',
    ];

    public static $listField = [
        'user_name' => '用户',
        'url' => 'URL',
        'data' => 'data',
        'route_name' => '路由名称',
        'ip' => 'IP',
        'ua' => 'userAgent',
    ];

    public function adminUser()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'admin_user_id');
    }
}
