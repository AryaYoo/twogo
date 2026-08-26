<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TripChatController extends Controller
{
    /**
     * Tampilkan ruang obrolan (Chat Room) untuk trip tertentu.
     */
    public function index(Trip $trip): View
    {
        $this->authorizeAccess($trip);

        // Ambil data partner dalam trip
        $currentUser = Auth::user();
        $partner = $trip->members()->where('users.id', '!=', $currentUser->id)->first();

        // Ambil riwayat pesan
        $messages = $trip->messages()
            ->with('user')
            ->orderBy('id', 'asc')
            ->get();

        // Tandai pesan dari partner sebagai telah dibaca
        $trip->messages()
            ->where('user_id', '!=', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('trips.chat', compact('trip', 'partner', 'messages'));
    }

    /**
     * Kirim pesan baru ke dalam room chat trip.
     */
    public function store(Request $request, Trip $trip): JsonResponse|RedirectResponse
    {
        $this->authorizeAccess($trip);

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $trip->messages()->create([
            'user_id' => Auth::id(),
            'message' => trim($validated['message']),
        ]);

        $message->load('user');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'user_avatar' => $message->user->avatar_url,
                    'message' => e($message->message),
                    'created_at' => $message->created_at->format('H:i'),
                    'is_mine' => true,
                ],
            ]);
        }

        return redirect()->route('trips.chat', $trip);
    }

    /**
     * Endpoint polling untuk mengambil pesan-pesan baru secara berkala (realtime).
     */
    public function fetchMessages(Request $request, Trip $trip): JsonResponse
    {
        $this->authorizeAccess($trip);

        $afterId = (int) $request->query('after_id', 0);
        $currentUserId = Auth::id();

        $newMessages = $trip->messages()
            ->with('user')
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        if ($newMessages->isNotEmpty()) {
            // Tandai pesan dari partner sebagai terbaca
            $trip->messages()
                ->where('user_id', '!=', $currentUserId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $formatted = $newMessages->map(function (TripMessage $msg) use ($currentUserId) {
            return [
                'id' => $msg->id,
                'user_id' => $msg->user_id,
                'user_name' => $msg->user->name,
                'user_avatar' => $msg->user->avatar_url,
                'message' => e($msg->message),
                'created_at' => $msg->created_at->format('H:i'),
                'is_mine' => $msg->user_id === $currentUserId,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
        ]);
    }

    /**
     * Memeriksa apakah pengguna yang sedang login berhak mengakses chat trip ini.
     */
    private function authorizeAccess(Trip $trip): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $isOwner = $trip->isOwner($user);
        $isMember = $trip->isMember($user);

        if (!$isOwner && !$isMember) {
            abort(403, 'Kamu bukan anggota dari perjalanan ini.');
        }
    }
}
