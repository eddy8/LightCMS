<?php
/**
 * Date: 2019/3/4 Time: 13:54
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * 搜索字段
     *
     * @var array
     */
    public static $searchField = [];

    /**
     * 列表字段
     *
     * @var array
     */
    public static $listField = [];

    /**
     * 列表操作项
     *
     * @var array
     */
    public static $actionField = [];

    /**
     * 搜索排序
     *
     * @var array
     */
    public static $sortFields = [];
}
