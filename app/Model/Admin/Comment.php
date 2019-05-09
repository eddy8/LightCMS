<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Comment extends Model
{
    public static $listField = [
        'entityName' => '模型',
        'content' => '内容',
        'reply_count' => '回复数',
        'like' => '喜欢',
        'dislike' => '不喜欢',
    ];

    public static $searchField = [
        'content' => '内容',
    ];

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    const ADMIN_YES = 1;
    const ADMIN_NO = 0;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Model\Admin\User', 'user_id');
    }

    public function replyUser()
    {
        return $this->belongsTo('App\Model\Admin\User', 'reply_user_id');
    }

    public function entity()
    {
        return $this->belongsTo('App\Model\Admin\Entity', 'entity_id');
    }
}
