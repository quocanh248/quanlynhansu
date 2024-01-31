<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleHoso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {      
        if (auth()->user() && in_array(auth()->user()->role, ['admin', 'hoso', 'hanhchinh'])) {
            return $next($request);        }
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
    }
}
