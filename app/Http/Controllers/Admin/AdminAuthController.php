<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.overview');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));

        // Check user by email or name (username)
        $user = User::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'login' => 'Kredensial login (username/email atau password) salah.',
            ])->withInput($request->only('login'));
        }

        if (!$user->isAdmin()) {
            return back()->withErrors([
                'login' => 'Akun Anda tidak memiliki hak akses Administrator.',
            ])->withInput($request->only('login'));
        }

        if ($user->isSuspended() || $user->isBanned()) {
            return back()->withErrors([
                'login' => 'Akun Admin ini sedang ditangguhkan atau dinonaktifkan.',
            ])->withInput($request->only('login'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);

        return redirect()->route('admin.overview')->with('success', 'Selamat datang kembali, Admin ' . $user->name . '! 👋');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil keluar dari panel Admin.');
    }
}
