<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class ForYouController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->friends()->pluck('id')->push(Auth::id())->unique();

        $tripsQuery = Trip::with(['creator', 'likes'])->where('is_public', true);
        
        // Ambils trip teman/sendiri terlebih dahulu
        $friendTrips = (clone $tripsQuery)->whereIn('user_id', $userIds)->orderByDesc('created_at')->limit(30)->get();
        
        // Jika publik feed teman < 5 item, ambil seluruh trip publik terbaru secara global (FYP)
        if ($friendTrips->count() < 5) {
            $feedTrips = $tripsQuery->orderByDesc('created_at')->limit(30)->get();
        } else {
            $feedTrips = $friendTrips;
        }

        $feed = $feedTrips->map(function (Trip $trip) {
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
                'is_liked'   => $trip->likes->contains('user_id', Auth::id()),
                'created_at' => $trip->created_at,
                'image_url'  => $imageUrl,
            ];
        });

        return view('for-you.index', compact('feed'));
    }
}
