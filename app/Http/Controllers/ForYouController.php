<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class ForYouController extends Controller
{
    public function index()
    {
        $friendIds = Auth::user()->friends()->pluck('id');

        $feed = collect();

        if ($friendIds->isNotEmpty()) {
            $feed = Trip::with(['creator', 'likes'])
                ->whereIn('user_id', $friendIds)
                ->where('is_public', true)
                ->orderByDesc('created_at')
                ->limit(30)
                ->get()
                ->map(fn (Trip $trip) => [
                    'type'       => $trip->start_date ? 'trip' : 'wishlist',
                    'trip'       => $trip,
                    'user'       => $trip->creator,
                    'created_at' => $trip->created_at,
                ]);
        }

        return view('for-you.index', compact('feed'));
    }
}
