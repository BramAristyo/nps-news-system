<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $role = $request->user()->role;
            
            if (in_array($role, ['admin', 'editor'])) {
                if ($request->path() === '/' || $request->path() === '/news') {
                    return redirect('/dashboard');
                }
            }
            
            if ($role === 'user') {
                if ($request->path() === '/dashboard') {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
