<?php

namespace App\Http\Middleware;

use App\Repository\Admin\LogRepository;
use Closure;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Support\Facades\Auth;

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
        if ($guard !== '') {
            $user = Auth::guard($guard)->user();
            if ($user) {
                $data['user_id'] = $user->id;
                $data['user_name'] = $user->name;
            }
        }
        $data['url'] = $request->url();
        $data['ua'] = $request->userAgent();
        $data['ip'] = (string) $request->getClientIp();
        $input = $request->all();
        if (isset($input['password'])) {
            $input['password'] = '******';
        }
        $data['data'] = build_query($input, false);
        LogRepository::add($data);
        return $next($request);
    }
}
