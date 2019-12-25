<?php

namespace App\Http\Controllers;

use App\Repository\Admin\MenuRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;
use App\Repository\Admin\EntityRepository;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $breadcrumb = [];

    protected $formNames = [];

    public function __construct()
    {
        if (request()->ajax()) {
            return;
        }

        // 面包屑导航
        $this->breadcrumb[] = ['title' => '首页', 'url' => route('admin::index')];
        View::share('breadcrumb', $this->breadcrumb);


        // 菜单
        $route = request()->route();
        if (is_null($route)) {
            return;
        }
        $route = request()->route()->getName();
        View::share('light_cur_route', $route);
        if (is_null($currentRootMenu = MenuRepository::root($route))) {
            View::share('light_menu', []);
        } else {
            View::share('light_menu', $currentRootMenu);
            if ($route !== 'admin::aggregation' && $currentRootMenu['route'] === 'admin::aggregation') {
                View::share('autoMenu', EntityRepository::systemMenu());
            }
        }

        $this->formNames = array_merge($this->formNames, ['created_at']);
    }
}
