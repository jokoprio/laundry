<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin'])) {
            return $next($request);
        }

        // If not authenticated or not authorized, redirect or abort
        return redirect()->route('login')->withErrors(['email' => 'Access Denied. Administrator access only.']);
    }
}
