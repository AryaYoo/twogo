<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function buildProfileData($user, $viewingUser)
    {
        $isOwn = $viewingUser && $viewingUser->id === $user->id;

        // Trips & wishlists
        $allTrips = $user->trips()->with(['members', 'likes', 'documents', 'days.activities'])->orderByDesc('created_at')->get();
        $trips    = $allTrips->whereNotNull('start_date')
                             ->when(!$isOwn, fn($c) => $c->where('is_public', true))
                             ->values();
        $wishlists = $allTrips->whereNull('start_date')
                              ->when(!$isOwn, fn($c) => $c->where('is_public', true))
                              ->values();

        // Stats
        $friendsCount = $user->friends()->count();
        $tripsCount   = $isOwn ? $allTrips->whereNotNull('start_date')->count() : $trips->count();
        $wishlistCount = $isOwn ? $allTrips->whereNull('start_date')->count() : $wishlists->count();

        $friendshipStatus = 'self';
        $friendshipInitiator = null;
        if (!$isOwn && $viewingUser) {
            $friendship = Friendship::where(function ($q) use ($user, $viewingUser) {
                $q->where('user_id', $viewingUser->id)->where('friend_id', $user->id);
            })->orWhere(function ($q) use ($user, $viewingUser) {
                $q->where('user_id', $user->id)->where('friend_id', $viewingUser->id);
            })->first();

            $friendshipStatus = $friendship ? $friendship->status : 'none';
            $friendshipInitiator = $friendship?->user_id;
        }

        return compact(
            'user', 'trips', 'wishlists', 'friendsCount', 'tripsCount', 'wishlistCount', 'isOwn',
            'friendshipStatus', 'friendshipInitiator'
        );
    }

    public function show()
    {
        $user = Auth::user();
        $data = $this->buildProfileData($user, $user);
        return view('profile.show', $data);
    }

    public function gamification(?\App\Models\User $user = null)
    {
        $user = $user ?? Auth::user();
        return view('profile.gamification', compact('user'));
    }

    public function showUser(\App\Models\User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('profile.show');
        }

        $data = $this->buildProfileData($user, Auth::user());
        return view('profile.show', $data);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only('name', 'email', 'phone', 'bio');
        
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diupdate!');
    }
}
