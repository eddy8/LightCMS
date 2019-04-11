<?php

namespace App\Repository\Front;

use App\Model\Admin\Comment;
use App\Model\Admin\CommentOperateLog;
use Illuminate\Support\Facades\DB;

class CommentRepository
{
    public static function like(int $id, int $uid)
    {
        self::operate($id, $uid, 'like');
        return self::info($id, $uid);
    }

    public static function dislike(int $id, int $uid)
    {
        self::operate($id, $uid, 'dislike');
        return self::info($id, $uid);
    }

    public static function neutral(int $id, int $uid)
    {
        DB::transaction(function () use ($id, $uid) {
            $logs = CommentOperateLog::query()->select('operate')->
                where('user_id', $uid)->where('comment_id', $id)->get();
            if ($logs->isEmpty()) {
                // 未操作则直接返回
                return true;
            }

            foreach ($logs as $log) {
                if ($log->operate === 'like') {
                    Comment::query()->where('id', $id)->decrement('like');
                } elseif ($log->operate === 'dislike') {
                    Comment::query()->where('id', $id)->decrement('dislike');
                }
            }

            return CommentOperateLog::query()->where('user_id', $uid)->where('comment_id', $id)->delete();
        });

        return self::info($id, $uid);
    }

    public static function operate(int $id, int $uid, string $operate)
    {
        return DB::transaction(function () use ($id, $uid, $operate) {
            $log = CommentOperateLog::query()->where('user_id', $uid)->where('comment_id', $id)
                ->where('operate', $operate)->first();
            if ($log) {
                // 已操作则直接返回
                return true;
            }

            $oppositeOperate = $operate === 'like' ? 'dislike' : 'like';
            $log = CommentOperateLog::query()->where('user_id', $uid)->where('comment_id', $id)
                ->where('operate', $oppositeOperate)->first();
            if ($log) {
                // 存在反操作则删除，并递减数量
                $log->delete();
                Comment::query()->where('id', $id)->decrement($oppositeOperate);
            }

            CommentOperateLog::query()->create(['user_id' => $uid, 'comment_id' => $id, 'operate' => $operate]);
            return Comment::query()->where('id', $id)->increment($operate);
        });
    }

    /**
     * 返回指定用户对评论的操作情况及评论操作数据
     *
     * @param int $id
     * @param int $uid
     * @return array
     */
    public static function info(int $id, int $uid = 0)
    {
        $data = [
            'like' => 0,
            'dislike' => 0,
        ];
        $comment = Comment::query()->select(['like', 'dislike'])->where('id', $id)->first();
        if (!$comment) {
            return $data;
        }
        $data['like'] = $comment->like;
        $data['dislike'] = $comment->dislike;

        if ($uid > 0) {
            $data['operate'] = [
                'like' => false,
                'dislike' => false,
            ];
            $operates = CommentOperateLog::query()->select('operate')
                ->where('user_id', $uid)->where('comment_id', $id)->get();
            foreach ($operates as $operate) {
                $data['operate'][$operate->operate] = true;
            }
        }

        return $data;
    }
}
