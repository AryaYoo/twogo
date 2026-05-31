<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function showInviteForm(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);
        
        $friends = Auth::user()->friends();
        
        // Filter out friends who are already members
        $memberIds = $trip->members->pluck('id')->toArray();
        $availableFriends = $friends->filter(function($friend) use ($memberIds) {
            return !in_array($friend->id, $memberIds);
        });
        
        return view('trips.invite', compact('trip', 'availableFriends'));
    }

    public function inviteViaCode(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|size:6'
        ]);

        $trip = Trip::where('invite_code', strtoupper($request->invite_code))->first();

        if (!$trip) {
            return back()->withErrors(['invite_code' => 'Kode invite tidak valid.']);
        }

        if ($trip->members()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('trips.show', $trip)->with('info', 'Kamu sudah bergabung dalam trip ini.');
        }

        if ($trip->members()->count() >= 2) {
            return back()->withErrors(['invite_code' => 'Maaf, trip ini sudah penuh (Maks 2 orang).']);
        }

        $trip->members()->attach(Auth::id(), [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->route('trips.show', $trip)->with('success', 'Berhasil bergabung dengan trip!');
    }
}
