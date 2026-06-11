<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Notifications\AppActivityNotification;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $friends = $user->friends();
        
        $pendingRequests = Friendship::where('friend_id', $user->id)
                                    ->where('status', 'pending')
                                    ->with('user')
                                    ->get();
                                    
        $sentRequests = Friendship::where('user_id', $user->id)
                                 ->where('status', 'pending')
                                 ->with('friend')
                                 ->get();
                                 
        return view('friends.index', compact('friends', 'pendingRequests', 'sentRequests'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $users = collect();
        
        if ($query && strlen($query) >= 3) {
            $users = User::where('id', '!=', Auth::id())
                        ->where(function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%")
                              ->orWhere('email', 'like', "%{$query}%");
                        })
                        ->get();
                        
            // Append friendship status
            $users->map(function($user) {
                $status = Friendship::where(function($q) use ($user) {
                    $q->where('user_id', Auth::id())->where('friend_id', $user->id);
                })->orWhere(function($q) use ($user) {
                    $q->where('user_id', $user->id)->where('friend_id', Auth::id());
                })->first();
                
                $user->friendship_status = $status ? $status->status : 'none';
                $user->friendship_initiator = $status ? $status->user_id : null;
                return $user;
            });
        }
        
        return view('friends.index', ['searchResults' => $users, 'query' => $query]);
    }

    public function sendRequest(User $friend)
    {
        if ($friend->id === Auth::id()) return back();
        
        $exists = Friendship::where(function($q) use ($friend) {
            $q->where('user_id', Auth::id())->where('friend_id', $friend->id);
        })->orWhere(function($q) use ($friend) {
            $q->where('user_id', $friend->id)->where('friend_id', Auth::id());
        })->exists();
        
        if (!$exists) {
            Friendship::create([
                'user_id' => Auth::id(),
                'friend_id' => $friend->id,
                'status' => 'pending'
            ]);

            // Notify the recipient
            $friend->notify(new AppActivityNotification(
                Auth::user()->name . " mengirim permintaan pertemanan kepadamu! 👤",
                '👤',
                route('friends.index'),
                'friend_request'
            ));

            return back()->with('success', 'Permintaan pertemanan dikirim!');
        }
        
        return back();
    }

    public function acceptRequest(Friendship $friendship)
    {
        if ($friendship->friend_id !== Auth::id()) abort(403);
        
        $friendship->update(['status' => 'accepted']);

        $user = Auth::user();
        
        // Notify the requester
        $friendship->user->notify(new AppActivityNotification(
            "{$user->name} menerima permintaan pertemananmu! 🤝",
            '🤝',
            route('profile.user', $user),
            'friend_connected'
        ));

        // Notify the acceptor
        $user->notify(new AppActivityNotification(
            "Kamu sekarang berteman dengan {$friendship->user->name}! 🤝",
            '🤝',
            route('profile.user', $friendship->user),
            'friend_connected'
        ));

        // Award XP to both users for connecting
        GamificationService::awardXp($friendship->user, 'friend_added', null, $user);
        GamificationService::awardXp($user, 'friend_added', null, $friendship->user);

        return back()->with('success', 'Permintaan pertemanan diterima!');
    }

    public function declineRequest(Friendship $friendship)
    {
        if ($friendship->friend_id !== Auth::id() && $friendship->user_id !== Auth::id()) abort(403);
        
        $friendship->delete();
        return back()->with('success', 'Permintaan pertemanan ditolak.');
    }

    public function remove(User $friend)
    {
        Friendship::where(function($q) use ($friend) {
            $q->where('user_id', Auth::id())->where('friend_id', $friend->id);
        })->orWhere(function($q) use ($friend) {
            $q->where('user_id', $friend->id)->where('friend_id', Auth::id());
        })->delete();
        
        return back()->with('success', 'Teman dihapus.');
    }
}
