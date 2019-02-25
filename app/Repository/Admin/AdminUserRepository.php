<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;


use App\Model\Admin\AdminUser;

class AdminUserRepository
{
    public static function list($perPage)
    {
        $data = AdminUser::query()->paginate($perPage);

        return [
            'code' => 0,
            'msg' => '',
            'count' => $data->total(),
            'data' => $data->items(),
        ];
    }
}