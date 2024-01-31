<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class middlewarecheckadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && in_array(auth()->user()->role, ['admin'])) {
            return $next($request);        }
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
    }
}
