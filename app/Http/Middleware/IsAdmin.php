<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin middleware: only allow admin and owner roles.
        // Supervisor has a separate middleware (IsSupervisor) with its own scope.
        if ($request->user()->role === 'admin' || $request->user()->role === 'owner') return $next($request);
        return redirect()->route('dashboard')->with('status', 'Kamu tidak memiliki akses');
    }
}
