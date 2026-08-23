<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('ownedTrips');

        // Filter status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where(function ($q) {
                    $q->where('status', 'active')->orWhereNull('status');
                });
            } else {
                $query->where('status', $status);
            }
        }

        // Search name/email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        if (in_array($sort, ['name', 'email', 'xp', 'created_at', 'last_login_at'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $users = $query->paginate(15)->withQueryString();

        // Calculate level info for each user
        $users->getCollection()->transform(function ($user) {
            $user->level_info = GamificationService::getLevelInfo($user->xp ?? 0);
            return $user;
        });

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount(['ownedTrips', 'wishlistItems', 'documents']);
        $user->load(['trips' => function ($q) {
            $q->latest()->take(5);
        }]);

        $levelInfo = GamificationService::getLevelInfo($user->xp ?? 0);
        $bestPartners = GamificationService::getBestPartners($user);

        return response()->json([
            'user'          => $user,
            'level_info'    => $levelInfo,
            'best_partners' => $bestPartners,
            'trips_count'   => $user->owned_trips_count,
            'registered_at' => $user->created_at->format('d M Y H:i'),
            'last_login'    => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah login',
        ]);
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
        ]);

        // Prevent self ban
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menguji perubahan status pada akun Anda sendiri!');
        }

        $oldStatus = $user->status ?? 'active';
        $newStatus = $request->input('status');

        $user->update(['status' => $newStatus]);

        $statusText = match ($newStatus) {
            'active'    => 'diaktifkan kembali',
            'suspended' => 'ditangguhkan (suspended)',
            'banned'    => 'diblokir permanen (banned)',
        };

        return back()->with('success', "Status pengguna {$user->name} berhasil diubah menjadi: {$statusText}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return back()->with('success', "Password pengguna {$user->name} telah berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Akun pengguna {$userName} beserta seluruh datanya telah berhasil dihapus.");
    }
}
