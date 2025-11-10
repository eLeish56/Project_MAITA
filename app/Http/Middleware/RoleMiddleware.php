<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * RoleMiddleware checks whether the authenticated user's role is one of the allowed roles.
 * Usage in routes: ->middleware('role:admin,supervisor')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles = null)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // roles passed as comma separated string
        $allowed = array_map('trim', explode(',', $roles ?? ''));

        if (in_array($request->user()->role, $allowed, true)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('status', 'Kamu tidak memiliki akses');
    }
}
