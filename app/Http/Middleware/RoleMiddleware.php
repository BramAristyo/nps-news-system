<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles  Allowed roles (admin, editor, user)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            return abort(403, 'Unauthenticated');
        }
        $userRole = $user->role ?? 'user';
        if (empty($roles)) {
            return $next($request);
        }
        if (!in_array($userRole, $roles)) {
            return abort(403, 'Insufficient role');
        }
        return $next($request);
    }
}
