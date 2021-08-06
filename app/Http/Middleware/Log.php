<?php

namespace App\Http\Middleware;

use App\Repository\Admin\LogRepository;
use Carbon\Carbon;
use Closure;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Jobs\WriteSystemLog;

class Log
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = '')
    {
        $data = [];
        if ($guard !== '') {
            $user = Auth::guard($guard)->user();
            if ($user) {
                $data['user_id'] = $user->id;
                $data['user_name'] = $user->name;
            }
        }

        $route = Route::current();
        $data['route_name'] = (string) $route->getName();

        $data['url'] = mb_substr($request->url(), 0, 191);
        $data['ua'] = mb_substr((string) $request->userAgent(), 0, 512);
        $data['ip'] = mb_substr((string) $request->getClientIp(), 0, 15);
        $input = $request->all();
        if (isset($input['password'])) {
            $input['password'] = '******';
        }
        $data['data'] = Query::build($input, false);

        if (config('light.log_async_write')) {
            $data['updated_at'] = $data['created_at'] = Carbon::now();
            dispatch(new WriteSystemLog($data));
        } else {
            LogRepository::add($data);
        }
        return $next($request);
    }
}
