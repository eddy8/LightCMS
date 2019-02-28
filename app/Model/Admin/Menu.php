<?php
/**
 * Date: 2019/2/25 Time: 10:34
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo('App\Model\Admin\Menu', 'pid');
    }

    public function children()
    {
        return $this->hasMany('App\Model\Admin\Menu', 'pid');
    }
}