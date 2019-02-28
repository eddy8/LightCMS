<?php

namespace App\Http\Controllers;

use App\Repository\Admin\MenuRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Route;
use Illuminate\Support\Facades\View;
use Debugbar;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $breadcrumb = [];

    public function __construct()
    {
        // 面包屑导航
        $this->breadcrumb[] = ['title' => '首页', 'url' => route('admin::index')];
        View::share('breadcrumb', $this->breadcrumb);


        // 菜单
        $route = request()->route()->getName();
        if (is_null($currentRootMenu = MenuRepository::root($route))) {
            throw new \RuntimeException("当前路由（{$route}）未注册。请在菜单管理中添加/修改当前路由对应菜单。");
        }
        Debugbar::info($currentRootMenu);
        View::share('light_menu', $currentRootMenu);
    }
}
