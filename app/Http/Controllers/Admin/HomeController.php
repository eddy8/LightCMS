<?php
/**
 * Date: 2019/2/25 Time: 14:35
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * 首页
     */
    public function showIndex()
    {
        return view('admin.home.index');
    }

    /**
     * 测试
     */
    public function test()
    {
        dd(config('light_config.SITE_NAME'), true);
        return view('admin.home.index');
    }
}