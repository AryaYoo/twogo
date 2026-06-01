<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\InvitationReceivedNotification;
use Carbon\Carbon;

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

    public function sendInvite(Request $request, Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'invited_user_id' => 'required|integer|exists:users,id'
        ]);

        $invitedUser = User::find($request->invited_user_id);

        // ensure invited user is a friend
        if (!Auth::user()->friends()->contains('id', $invitedUser->id)) {
            return back()->withErrors(['invited_user_id' => 'User bukan temanmu.']);
        }

        // check capacity: members + pending invites must be < 2
        $existingMembers = $trip->members()->count();
        $pendingInvites = $trip->invitations()->where('status', 'pending')->count();
        if ($existingMembers + $pendingInvites >= 2) {
            return back()->withErrors(['invited_user_id' => 'Tidak bisa mengundang, kuota trip sudah penuh atau ada undangan aktif.']);
        }

        $token = Str::random(64);

        $inv = TripInvitation::create([
            'trip_id' => $trip->id,
            'invited_by' => Auth::id(),
            'invited_user_id' => $invitedUser->id,
            'status' => 'pending',
            'token' => $token,
            'expires_at' => Carbon::now()->addDays(3),
        ]);

        // notify user
        $invitedUser->notify(new InvitationReceivedNotification($inv, route('invitations.accept', ['token' => $token])));

        return back()->with('success', 'Undangan terkirim.');
    }

    public function acceptInvite($token)
    {
        $inv = TripInvitation::where('token', $token)->where('status', 'pending')->firstOrFail();
        if ($inv->isExpired()) abort(410, 'Undangan sudah kedaluwarsa');

        $trip = $inv->trip;
        if ($trip->members()->where('user_id', $inv->invited_user_id)->exists()) {
            $inv->update(['status' => 'accepted']);
            return redirect()->route('trips.show', $trip)->with('info', 'Kamu sudah bergabung.');
        }

        if ($trip->members()->count() >= 2) {
            return redirect()->route('trips.show', $trip)->with('error', 'Trip sudah penuh.');
        }

        $trip->members()->attach($inv->invited_user_id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        $inv->update(['status' => 'accepted']);

        return redirect()->route('trips.show', $trip)->with('success', 'Kamu berhasil bergabung ke trip!');
    }

    /**
     * Show in-app pending invitations for authenticated user.
     */
    public function index()
    {
        $invitations = TripInvitation::where('invited_user_id', Auth::id())
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->with(['trip', 'inviter'])
            ->get();

        return view('invitations.index', compact('invitations'));
    }

    /**
     * Accept invitation from in-app UI.
     */
    public function accept(TripInvitation $invitation)
    {
        if ($invitation->invited_user_id !== Auth::id()) abort(403);
        if ($invitation->isExpired()) return back()->with('error', 'Undangan sudah kedaluwarsa.');
        if ($invitation->status !== 'pending') return back()->with('info', 'Undangan sudah diproses.');

        $trip = $invitation->trip;
        if ($trip->members()->count() >= 2) return back()->with('error', 'Trip sudah penuh.');

        $trip->members()->attach(Auth::id(), ['role' => 'member', 'joined_at' => now()]);
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('trips.show', $trip)->with('success', 'Kamu berhasil bergabung ke trip!');
    }

    /**
     * Decline invitation from in-app UI.
     */
    public function decline(TripInvitation $invitation)
    {
        if ($invitation->invited_user_id !== Auth::id()) abort(403);
        if ($invitation->status !== 'pending') return back()->with('info', 'Undangan sudah diproses.');

        $invitation->update(['status' => 'declined']);
        return back()->with('success', 'Undangan ditolak.');
    }
}
