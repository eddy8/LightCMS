<?php

namespace App\Http\Controllers\Front;

use App\Repository\Front\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseController
{
    public function __construct()
    {
        //Auth::guard('member')->loginUsingId(1);
    }
    /**
     * 发布一条评论
     */
    public function save()
    {
    }

    /**
     * 获取评论
     */
    public function list()
    {
    }

    /**
     * 对评论进行操作
     *
     * @param int $id
     * @param string $action
     * @return array
     */
    public function operate($id, $action)
    {
        $result = CommentRepository::$action($id, Auth::guard('member')->id());

        return [
            'code' => 0,
            'msg' => '',
            'data' => $result
        ];
    }
}
