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
            ->map(function (Trip $trip) {
                $imageUrl = null;
                if ($trip->cover_image) {
                    $imageUrl = str_starts_with($trip->cover_image, 'assets/') || str_starts_with($trip->cover_image, 'storage/') 
                        ? asset($trip->cover_image) 
                        : asset('storage/' . $trip->cover_image);
                } else {
                    $imageUrl = $trip->id % 2 === 0 ? asset('assets/images/img1.webp') : asset('assets/images/img2.webp');
                }

                return [
                    'type'       => $trip->start_date ? 'trip' : 'wishlist',
                    'trip'       => $trip,
                    'user'       => $trip->creator,
                    'is_own'     => $trip->user_id === Auth::id(),
                    'created_at' => $trip->created_at,
                    'image_url'  => $imageUrl,
                ];
            });

        return view('for-you.index', compact('feed'));
    }
}
