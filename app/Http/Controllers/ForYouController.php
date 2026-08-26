<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ForYouController extends Controller
{
    public function index()
    {
        $userId  = Auth::id();
        $userIds = Auth::user()->friends()->pluck('id')->push($userId)->unique();

        /* ----------------------------------------------------------------
         | 1. Feed Trip / Wishlist publik
         * ---------------------------------------------------------------- */
        $tripsQuery = Trip::with(['creator', 'likes'])->where('is_public', true);

        $friendTrips = (clone $tripsQuery)
            ->whereIn('user_id', $userIds)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        $feedTrips = $friendTrips->count() < 5
            ? $tripsQuery->orderByDesc('created_at')->limit(30)->get()
            : $friendTrips;

        $tripItems = $feedTrips->map(function (Trip $trip) use ($userId) {
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
                'is_own'     => $trip->user_id === $userId,
                'is_liked'   => $trip->likes->contains('user_id', $userId),
                'created_at' => $trip->created_at,
                'image_url'  => $imageUrl,
                // activity-specific fields (null for trip/wishlist)
                'activity'   => null,
            ];
        });

        /* ----------------------------------------------------------------
         | 2. Feed Open Partner: Trip yang membuka lowongan partner
         * ---------------------------------------------------------------- */
        $openPartnerQuery = Trip::with(['creator', 'likes'])
            ->where('is_open_partner', true)
            ->whereHas('members', function ($q) {
                // Belum penuh (< 2 anggota)
            }, '<', 2);

        $friendOpenPartners = (clone $openPartnerQuery)
            ->whereIn('user_id', $userIds)
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get();

        $feedOpenPartners = $friendOpenPartners->count() < 3
            ? $openPartnerQuery->orderByDesc('updated_at')->limit(30)->get()
            : $friendOpenPartners;

        $openPartnerItems = $feedOpenPartners->map(function (Trip $trip) use ($userId) {
            $imageUrl = null;
            if ($trip->cover_image) {
                $imageUrl = str_starts_with($trip->cover_image, 'assets/') || str_starts_with($trip->cover_image, 'storage/')
                    ? asset($trip->cover_image)
                    : asset('storage/' . $trip->cover_image);
            } else {
                $imageUrl = $trip->id % 2 === 0 ? asset('assets/images/img1.webp') : asset('assets/images/img2.webp');
            }

            return [
                'type'       => 'open_partner',
                'trip'       => $trip,
                'user'       => $trip->creator,
                'is_own'     => $trip->user_id === $userId,
                'is_liked'   => $trip->likes->contains('user_id', $userId),
                'created_at' => $trip->updated_at ?? $trip->created_at,
                'image_url'  => $imageUrl,
                'activity'   => null,
            ];
        });

        /* ----------------------------------------------------------------
         | 3. Feed Aktivitas: sudah selesai + punya foto + is_public = true
         *    + trip-nya juga publik
         * ---------------------------------------------------------------- */
        $activitiesQuery = TripActivity::with(['day.trip.creator'])
            ->where('is_public', true)
            ->where('is_completed', true)
            ->whereNotNull('photo')
            ->whereHas('day.trip', fn ($q) => $q->where('is_public', true));

        // Prioritas teman/sendiri
        $friendActivities = (clone $activitiesQuery)
            ->whereHas('day.trip', fn ($q) => $q->whereIn('user_id', $userIds))
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get();

        $feedActivities = $friendActivities->count() < 5
            ? $activitiesQuery->orderByDesc('updated_at')->limit(30)->get()
            : $friendActivities;

        $activityItems = $feedActivities->map(function (TripActivity $activity) use ($userId) {
            $trip    = $activity->day->trip;
            $creator = $trip?->creator;
            if (!$trip || !$creator) return null;

            $photoUrl = asset('storage/' . $activity->photo);

            return [
                'type'       => 'activity',
                'trip'       => $trip,
                'user'       => $creator,
                'is_own'     => $trip->user_id === $userId,
                'is_liked'   => false, // aktivitas belum punya like
                'created_at' => $activity->updated_at, // waktu diselesaikan
                'image_url'  => $photoUrl,
                'activity'   => $activity,
            ];
        })->filter()->values();

        /* ----------------------------------------------------------------
         | 4. Gabungkan & sort by created_at DESC (hindari duplikat trip)
         * ---------------------------------------------------------------- */
        $openPartnerTripIds = $feedOpenPartners->pluck('id')->toArray();

        $filteredTripItems = $tripItems->reject(function ($item) use ($openPartnerTripIds) {
            return in_array($item['trip']->id, $openPartnerTripIds);
        });

        $feed = $openPartnerItems
            ->concat($filteredTripItems)
            ->concat($activityItems)
            ->sortByDesc('created_at')
            ->values();

        return view('for-you.index', compact('feed'));
    }
}
