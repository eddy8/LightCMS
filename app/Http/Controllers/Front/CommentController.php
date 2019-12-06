<?php

namespace App\Http\Controllers\Front;

use App\Repository\Admin\ContentRepository;
use App\Repository\Admin\EntityRepository;
use App\Repository\Front\CommentRepository;
use App\Model\Admin\CommentOperateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Throwable;
use Illuminate\Support\Facades\Log;

class CommentController extends BaseController
{
    /**
     * 发布一条评论
     *
     * @param Request $request
     * @param int $entityId 模型ID
     * @param int $contentId 内容ID
     * @return array
     */
    public function save(Request $request, $entityId, $contentId)
    {
        $result = $this->checkParam($entityId, $contentId);
        if ($result !== true) {
            return $result;
        }

        $content = (string) $request->post('content', '');
        // 暂不支持html
        $content = strip_tags($content);
        if ($content === '') {
            return [
                'code' => 5,
                'msg' => '评论内容不能为空',
            ];
        }
        if (mb_strlen($content) > 1024) {
            return [
                'code' => 6,
                'msg' => '评论内容过长',
            ];
        }
        $pid = (int) $request->post('pid', 0);
        if ($pid < 0) {
            return [
                'code' => 7,
                'msg' => 'invalid pid',
            ];
        }
        if ($pid > 0 && !($parentComment = \App\Repository\Admin\CommentRepository::find($pid))) {
            return [
                'code' => 8,
                'msg' => '引用评论不存在',
            ];
        }

        try {
            $rid = $pid === 0 ? $pid : ($parentComment->rid == 0 ? $parentComment->id : $parentComment->rid);
            \App\Repository\Admin\CommentRepository::add([
                'entity_id' => $entityId,
                'content_id' => $contentId,
                'pid' => $pid,
                'rid' => $rid,
                'content' => $content,
                'user_id' => Auth::guard('member')->id(),
                'reply_user_id' => $pid > 0 ? $parentComment->user_id : 0,
            ]);
            if ($rid > 0) {
                // 清除缓存
                Cache::forget('comment_replay:' . $rid);

                // 回复数+1
                CommentRepository::addReplyCount($rid);
            }
            return [
                'code' => 0,
                'msg' => '',
                'reload' => true,
            ];
        } catch (Throwable $e) {
            Log::error($e);
            return [
                'code' => 500,
                'msg' => '评论失败：内部错误',
            ];
        }
    }

    /**
     * 获取评论
     *
     * @param Request $request
     * @param int $entityId 模型ID
     * @param int $contentId 内容ID
     * @return array
     */
    public function list(Request $request, $entityId, $contentId)
    {
        $result = $this->checkParam($entityId, $contentId);
        if ($result !== true) {
            return $result;
        }

        $limit = (int) $request->get('limit', 10);
        $limit = ($limit > 0 && $limit <= 20) ? $limit : 10;
        $rid = (int) $request->get('rid', 0);

        $condition = [
            'content_id' => ['=', $contentId],
            'entity_id' => ['=', $entityId],
            'rid' => ['=', $rid],
        ];

        $data = CommentRepository::list($limit, $condition);

        return [
            'code' => 0,
            'msg' => '',
            'data' => $data
        ];
    }

    /**
     * 获取指定用户对评论的操作数据
     *
     * @param Request $request
     */
    public function operateLogs(Request $request)
    {
        $commentIds = $request->get('comment_ids', '');
        if (!preg_match('%^[1-9]\d*(,[1-9]\d*)*%', $commentIds)) {
            return [
                'code' => 1,
                'msg' => '参数错误'
            ];
        }

        $userId = Auth::guard('member')->id();
        $data = CommentOperateLog::query()->select('comment_id', 'operate')
                ->whereIn('comment_id', explode(',', $commentIds))
                ->where('user_id', $userId)->get();
        return [
            'code' => 0,
            'msg' => '',
            'data' => $data
        ];
    }

    /**
     * 对评论进行操作。喜欢、不喜欢、中性（取消喜欢、取消不喜欢）
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

    protected function checkParam($entityId, $contentId)
    {
        $entity = EntityRepository::external($entityId);
        if (!$entity) {
            return [
                'code' => 1,
                'msg' => '模型不存在或未启用评论',
            ];
        }

        ContentRepository::setTable($entity->table_name);
        if (!ContentRepository::find($contentId)) {
            return [
                'code' => 2,
                'msg' => '内容不存在',
            ];
        }

        return true;
    }
}
