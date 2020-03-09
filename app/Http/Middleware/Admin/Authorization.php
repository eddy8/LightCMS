<?php

namespace App\Http\Middleware\Admin;

use App\Model\Admin\Menu;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param   string
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $user = Auth::guard($guard)->user();
        if (in_array($user->id, config('light.superAdmin'))) {
            return $next($request);
        }

        $route = Route::current();
        $routeName = $route->getName();
        $permission = Menu::where('route', $routeName)->where('route_params', '')->first();
        if ($permission && $user->can($permission->name)) {
            return $next($request);
        }

        $routeParams = $route->parameters();
        if (empty($routeParams)) {
            return $next($request);
        }
        foreach ($routeParams as $k => $v) {
            $val = "{$k}:{$v}";
            break;
        }

        $permission = Menu::where('route', $routeName)->where('route_params', $val)->first();
        if ($permission && $user->can($permission->name)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['code' => 401, 'msg' => "未授权操作（路由别名：{$routeName}）"], 401);
        }
        abort(401, "未授权操作（路由别名：{$routeName}）");
    }
}
