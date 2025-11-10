<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminOrSupervisor
{
    /**
     * Handle an incoming request allowing admin, owner or supervisor.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role;
        if (in_array($role, ['admin','owner','supervisor'], true)) return $next($request);
        return redirect()->route('dashboard')->with('status', 'Kamu tidak memiliki akses');
    }
}
