<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class User extends \App\Model\Front\User
{
    public static $listField = [
        'phone' => '手机号',
        'statusText' => '状态'
    ];

    public static $searchField = [
        'phone' => '手机号'
    ];

    public function comments()
    {
        return $this->hasMany('App\Model\Admin\Comment', 'user_id');
    }
}
