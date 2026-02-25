<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class MaintenanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $maintenanceMode = Setting::getValue('maintenance_mode', '0');

        if ($maintenanceMode === '1') {
            // Allow admins to pass through
            if (auth()->check() && auth()->user()->isAdmin()) {
                return $next($request);
            }
            // Allow access to login page
            if ($request->routeIs('login') || $request->routeIs('admin.*')) {
                return $next($request);
            }
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
