<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and has admin role
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // Allow admin to access user order details and related routes
            $allowedUserRoutes = [
                'user/orders/*',
                'orders/*/print-payslip',
                'orders/*/download-payslip',
                'user/products/*',
                'cart/*',
                'feedback/*',
                'profile/*'
            ];
            
            // Check if current route matches any allowed user routes
            $isAllowedUserRoute = false;
            foreach ($allowedUserRoutes as $pattern) {
                if ($request->is($pattern)) {
                    $isAllowedUserRoute = true;
                    break;
                }
            }
            
            // Only redirect if we're not already on the admin dashboard, not trying to logout, 
            // not on allowed user routes, and not a POST request
            if (!$request->is('admin*') && !$request->is('logout') && !$isAllowedUserRoute && $request->method() !== 'POST') {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}