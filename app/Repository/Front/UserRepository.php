<?php
/**
 * Date: 2019/4/3 Time: 9:33
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Front;

use App\Model\Front\User;

class UserRepository
{
    public static function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        return User::query()->create($data);
    }
}