<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCashier
{
    /**
     * Allow cashier and internal roles who can perform POS actions.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role;
        if (in_array($role, ['cashier','admin','owner','supervisor'], true)) return $next($request);
        return redirect()->route('dashboard')->with('status', 'Kamu tidak memiliki akses');
    }
}
