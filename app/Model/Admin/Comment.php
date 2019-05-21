<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Comment extends Model
{
    public static $listField = [
        'pid' => ['title' => '父ID', 'width' => 80],
        'entityName' => ['title' => '模型', 'width' => 100],
        'userName' => ['title' => '用户名', 'width' => 100],
        'content' => ['title' => '内容', 'width' => 400],
        'reply_count' => ['title' => '回复数', 'width' => 80, 'sort' => true],
        'like' => ['title' => '喜欢', 'width' => 80, 'sort' => true],
        'dislike' => ['title' => '不喜欢', 'width' => 80, 'sort' => true],
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
