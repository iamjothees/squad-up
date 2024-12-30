<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( !auth()->user()->hasRole('admin') ) {
            auth()->logout();
            throw new Exception("You don't have admin access");
            // return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
