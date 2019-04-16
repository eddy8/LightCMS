<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class Comment extends Model
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Model\Admin\User', 'user_id');
    }

    public function replyUser()
    {
        return $this->belongsTo('App\Model\Admin\User', 'reply_user_id');
    }

    public function adminUser()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'user_id');
    }

    public function entity()
    {
        return $this->belongsTo('App\Model\Admin\Entity', 'entity_id');
    }
}
