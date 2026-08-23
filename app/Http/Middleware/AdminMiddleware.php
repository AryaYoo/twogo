<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request for Admin pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai Admin terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->isSuspended() || $user->isBanned()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('error', 'Akun Anda dinonaktifkan atau ditangguhkan.');
        }

        if (!$user->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Anda tidak memiliki hak akses Admin.');
        }

        return $next($request);
    }
}
