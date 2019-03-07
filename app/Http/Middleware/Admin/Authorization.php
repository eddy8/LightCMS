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
        $route = Route::currentRouteName();
        $permission = Menu::where('route', $route)->first();
        if (!in_array($user->id, config('light.superAdmin')) && !$user->can($permission->name)) {
            if ($request->expectsJson()) {
                return response()->json(['code' => 401, 'msg' => "未授权操作（路由别名：{$route}）"], 401);
            }
            abort(401, "未授权操作（路由别名：{$route}）");
        }
        return $next($request);
    }
}
