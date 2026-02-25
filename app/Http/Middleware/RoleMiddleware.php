<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->is_banned) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended.');
        }

        if (! in_array($user->role, $roles)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
