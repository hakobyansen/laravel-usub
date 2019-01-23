<?php

namespace App\Http\Middleware;

use Closure;

class UsubSignIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // TODO: implement request validation, e.g. check if user is admin, or whatever you need to check

        return $next($request);
    }
}