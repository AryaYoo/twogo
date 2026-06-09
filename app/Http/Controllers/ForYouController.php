<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class ForYouController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->friends()->pluck('id')->push(Auth::id())->unique();

        $feed = Trip::with(['creator', 'likes'])
            ->whereIn('user_id', $userIds)
            ->where('is_public', true)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get()
            ->map(fn (Trip $trip) => [
                'type'       => $trip->start_date ? 'trip' : 'wishlist',
                'trip'       => $trip,
                'user'       => $trip->creator,
                'is_own'     => $trip->user_id === Auth::id(),
                'created_at' => $trip->created_at,
            ]);

        return view('for-you.index', compact('feed'));
    }
}
