<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Mega menu — halaman utama search.
     */
    public function index()
    {
        return view('search.index');
    }

    /**
     * Halaman cari trip & pengguna.
     */
    public function cari(Request $request)
    {
        $query = trim($request->get('q', ''));
        $trips = collect();
        $users = collect();

        if ($query && mb_strlen($query) >= 2) {
            $userId = Auth::id();

            $trips = Trip::with(['creator', 'likes', 'members'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('destination', 'like', "%{$query}%");
                })
                ->where(function ($q) use ($userId) {
                    $q->where('is_public', true)
                      ->orWhereHas('members', fn ($m) => $m->where('users.id', $userId));
                })
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            $users = User::where('id', '!=', $userId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->orderBy('name')
                ->limit(20)
                ->get();
        }

        return view('search.cari', compact('query', 'trips', 'users'));
    }

    /**
     * Halaman bergabung via kode perjalanan.
     */
    public function kode()
    {
        return view('search.kode');
    }

    /**
     * Halaman Open Partner (coming soon).
     */
    public function partner()
    {
        return view('search.partner');
    }

    /**
     * Halaman Trip Populer (coming soon).
     */
    public function populer()
    {
        return view('search.populer');
    }
}
