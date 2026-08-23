<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\XpLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminOverviewController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // 1. Stats Cards
        $totalActiveUsers = User::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->count();

        $totalUsers = User::count();

        $newUsersDaily = User::whereDate('created_at', Carbon::today())->count();
        $newUsersWeekly = User::where('created_at', '>=', $now->copy()->subDays(7))->count();
        $newUsersMonthly = User::where('created_at', '>=', $now->copy()->subDays(30))->count();

        $totalItineraries = Trip::count();
        $publishedItineraries = Trip::where('is_public', true)->count();
        $draftItineraries = Trip::where('is_public', false)->count();
        $flaggedItineraries = Trip::where('is_flagged', true)->count();

        $totalXpCirculating = User::sum('xp');

        // 2. User Growth Chart Data (Last 14 days)
        $chartLabels = [];
        $chartValues = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $count = User::whereDate('created_at', $dateStr)->count();
            
            $chartLabels[] = $date->format('d M');
            $chartValues[] = $count;
        }

        // 3. Activity Feed (Unified Recent Activities)
        $recentUsers = User::latest()->take(5)->get()->map(function ($u) {
            return [
                'type'       => 'user_registered',
                'title'      => 'Pengguna Baru Terdaftar',
                'description'=> "{$u->name} ({$u->email}) bergabung dengan TwoGo",
                'timestamp'  => $u->created_at,
                'badge_bg'   => '#00D4AA',
                'icon'       => '👤',
            ];
        });

        $recentTrips = Trip::with('creator')->latest()->take(5)->get()->map(function ($t) {
            $creatorName = $t->creator ? $t->creator->name : 'Seseorang';
            return [
                'type'       => 'trip_created',
                'title'      => 'Itinerary Baru Dibuat',
                'description'=> "{$creatorName} membuat itinerary \"{$t->title}\" ke {$t->destination}",
                'timestamp'  => $t->created_at,
                'badge_bg'   => '#FFE156',
                'icon'       => '✈️',
            ];
        });

        $recentXpLogs = XpLog::with('user')->latest()->take(5)->get()->map(function ($log) {
            $userName = $log->user ? $log->user->name : 'User';
            return [
                'type'       => 'xp_earned',
                'title'      => 'XP Diperoleh',
                'description'=> "{$userName} memperoleh +{$log->amount} XP ({$log->description})",
                'timestamp'  => $log->created_at,
                'badge_bg'   => '#FF6B9D',
                'icon'       => '⭐',
            ];
        });

        $activities = $recentUsers
            ->concat($recentTrips)
            ->concat($recentXpLogs)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        return view('admin.overview', compact(
            'totalActiveUsers',
            'totalUsers',
            'newUsersDaily',
            'newUsersWeekly',
            'newUsersMonthly',
            'totalItineraries',
            'publishedItineraries',
            'draftItineraries',
            'flaggedItineraries',
            'totalXpCirculating',
            'chartLabels',
            'chartValues',
            'activities'
        ));
    }
}
