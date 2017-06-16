<?php

namespace Bhutanio\Laravel\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class RemoveStaleCookies
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            $this->killDeadCookies($request);
        }

        return $next($request);
    }

    private function killDeadCookies($request)
    {
        if (!empty($request->cookie())) {
            foreach ($request->cookie() as $key => $cookie) {
                if (strpos($key, 'remember_') !== false) {
                    Cookie::queue($key, null, -9999);
                }
            }
        }
    }
}
