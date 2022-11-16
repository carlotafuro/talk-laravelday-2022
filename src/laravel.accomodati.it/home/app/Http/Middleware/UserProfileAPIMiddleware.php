<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserProfileAPIMiddleware
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
    public function handle(Request $request, Closure $next)
    {
        $basic_authorization = 'Basic ' . base64_encode('remote_api_user:123456789');

        if ($request->hasHeader('authorization') and $request->header('authorization') == $basic_authorization) {
            return $next($request);
        } else {
            return response()->json(['result' => false, 'error' => 'api auth error']);
        }
    }
}