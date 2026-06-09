<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
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

        return view('search.index', compact('query', 'trips', 'users'));
    }
}
