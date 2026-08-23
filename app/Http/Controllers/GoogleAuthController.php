<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user exists with this google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                Auth::login($user);
                return redirect()->intended('dashboard')->with('success', 'Berhasil masuk dengan Google! Selamat merencanakan perjalanan 🚀');
            } else {
                // Check if user exists with this email
                $existingUser = User::where('email', $googleUser->email)->first();
                
                if ($existingUser) {
                    // Link account
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $existingUser->avatar ?? $googleUser->avatar,
                    ]);
                    
                    Auth::login($existingUser);
                    return redirect()->intended('dashboard')->with('success', 'Berhasil menghubungkan akun Google! Selamat merencanakan perjalanan 🚀');
                } else {
                    // Create new user
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => null,
                    ]);
                    
                    Auth::login($newUser);
                    return redirect()->intended('dashboard')->with('success', 'Berhasil mendaftar dengan Google! Selamat merencanakan perjalanan 🚀');
                }
            }
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage()
            ]);
        }
    }
}
