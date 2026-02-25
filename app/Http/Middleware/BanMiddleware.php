<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_banned) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Contact support for assistance.');
        }

        return $next($request);
    }
}
