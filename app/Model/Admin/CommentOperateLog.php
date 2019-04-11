<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class CommentOperateLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Model\Front\User', 'user_id');
    }

    public function comment()
    {
        return $this->belongsTo('App\Model\Admin\Comment', 'comment_id');
    }
}
