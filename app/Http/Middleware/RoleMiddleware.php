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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = array_slice(func_get_args(), 2);
        $user = $request->user();

        if (! $user || ($user->role !== 'admin' && ! in_array($user->role, $roles, true))) {
            abort(403, 'Role Anda tidak punya akses ke modul ini.');
        }

        return $next($request);
    }
}
