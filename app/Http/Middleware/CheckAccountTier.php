<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountTier
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $tier = 'early_access'): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin bypasses all checks
        if ($user->isAdmin()) {
            return $next($request);
        }

        $allowedTiers = [];
        if ($tier === 'premium') {
            $allowedTiers = ['premium'];
        } elseif ($tier === 'early_access') {
            $allowedTiers = ['early_access', 'premium'];
        } else {
            $allowedTiers = ['standard', 'early_access', 'premium'];
        }

        if (!in_array($user->account_tier, $allowedTiers)) {
            return redirect()->back()->with('error', 'Fitur ini membutuhkan akses tier ' . ucwords(str_replace('_', ' ', $tier)) . ' atau lebih tinggi.');
        }

        return $next($request);
    }
}
