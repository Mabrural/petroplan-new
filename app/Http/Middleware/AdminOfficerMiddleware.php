<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOfficerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->rolePermissions->contains('permission', 'admin_officer')) {
            abort(403, 'Unauthorized. Admin Officer only.');
        }

        return $next($request);
    }
}
