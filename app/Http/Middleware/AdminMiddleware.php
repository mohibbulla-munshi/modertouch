<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->is_banned) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended. Reason: ' . ($user->ban_reason ?? 'Policy violation'));
        }

        if (! $user->isManager()) {
            abort(403, 'Access Denied. You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
