<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Branch;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user || !$user->tenant) {
            return $next($request);
        }

        $tenant = $user->tenant;
        $isMainActive = $tenant->subscription_expires_at && $tenant->subscription_expires_at->isFuture();

        // Context 1: Accessing a specific branch data
        $activeBranchId = session('active_branch_id');
        if ($activeBranchId) {
            $branch = Branch::find($activeBranchId);
            if ($branch) {
                $isBranchActive = $branch->isSubscriptionActive();

                // Rule: POS is accessible if Main OR Branch is active.
                // Other business operations might require Main to be active.
                if ($request->is('owner/transactions*') || $request->is('owner/pos*')) {
                    if (!$isMainActive && !$isBranchActive) {
                        return redirect()->route('owner.dashboard')->with('error', 'Masa aktif langganan utama dan cabang telah berakhir. Silakan lakukan perpanjangan.');
                    }
                } else {
                    // Non-POS operations usually depend on the main account
                    if (!$isMainActive) {
                        return redirect()->route('owner.dashboard')->with('error', 'Akses terbatas. Silakan perpanjang langganan utama Anda.');
                    }

                    // Specific branch data access requires the branch to be active too?
                    // Typically yes, if it's an addon.
                    if (!$isBranchActive && !$request->is('owner/branches*')) {
                        return redirect()->route('owner.branches.index')->with('error', 'Masa aktif cabang ini telah berakhir.');
                    }
                }
            }
        } else {
            // Context 2: General access (Pusat)
            if (!$isMainActive && !$request->is('owner/dashboard') && !$request->is('owner/profile*') && !$request->is('owner/settings*')) {
                return redirect()->route('owner.dashboard')->with('error', 'Langganan Anda telah berakhir. Harap lakukan perpanjangan.');
            }
        }

        return $next($request);
    }
}
