<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperasionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->rolePermissions->contains('permission', 'operasion')) {
            abort(403, 'Unauthorized. Operasion only.');
        }

        return $next($request);
    }
}
