<?php
/**
 * Date: 2019/4/3 Time: 9:33
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Front;

use App\Model\Front\User;
use App\Model\Front\UserAuth;

class UserRepository
{
    public static function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        return User::query()->create($data);
    }

    public static function createAuth($userId, \Overtrue\Socialite\User $user)
    {
        $type = strtolower($user->getProviderName());
        if (!isset(UserAuth::AUTH_TYPE_NAME[$type])) {
            throw new \InvalidArgumentException('三方授权类型未注册');
        }
        $data = [];
        $data['type'] = UserAuth::AUTH_TYPE_NAME[$type];

        $data['user_id'] = $userId;
        $data['openid'] = $user->getId();
        $data['avatar'] = (string) $user->getAvatar();
        $data['username'] = (string) $user->getName();
        $data['nick_name'] = (string) $user->getNickname();
        $data['email'] = (string) $user->getEmail();

        return UserAuth::query()->create($data);
    }
}
