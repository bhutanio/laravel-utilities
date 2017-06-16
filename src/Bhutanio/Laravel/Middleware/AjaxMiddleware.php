<?php

namespace Bhutanio\Laravel\Middleware;

use Closure;

class AjaxMiddleware
{
    public function handle($request, Closure $next)
    {
        if (app()->environment() != 'local' && !$request->expectsJson()) {
            return response('Not Allowed.', 405);
        }

        return $next($request);
    }
}
