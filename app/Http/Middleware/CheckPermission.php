<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
