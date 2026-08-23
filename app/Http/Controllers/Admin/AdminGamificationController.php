<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\XpLog;
use App\Models\XpRule;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class AdminGamificationController extends Controller
{
    public function index(Request $request)
    {
        // 1. Leaderboard Users
        $leaderboard = User::orderByDesc('xp')
            ->orderBy('name')
            ->take(50)
            ->get()
            ->map(function ($user, $index) {
                $user->rank = $index + 1;
                $user->level_info = GamificationService::getLevelInfo($user->xp ?? 0);
                return $user;
            });

        // 2. XP Rules
        $xpRules = XpRule::orderBy('id')->get();
        if ($xpRules->isEmpty()) {
            // Fallback default rules if table empty
            foreach (GamificationService::REWARDS as $key => $amount) {
                XpRule::create([
                    'key'         => $key,
                    'name'        => str_replace('_', ' ', ucfirst($key)),
                    'xp_amount'   => $amount,
                    'is_active'   => true,
                ]);
            }
            $xpRules = XpRule::orderBy('id')->get();
        }

        // 3. Recent XP Logs (Transaction Logs)
        $logsQuery = XpLog::with(['user', 'partner']);
        
        if ($request->filled('source_type')) {
            $logsQuery->where('source_type', $request->input('source_type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $logsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $xpLogs = $logsQuery->latest()->paginate(15, ['*'], 'logs_page')->withQueryString();

        // 4. Badges / Tiers Overview
        $tiers = GamificationService::getAllTiers();

        return view('admin.gamification.index', compact('leaderboard', 'xpRules', 'xpLogs', 'tiers'));
    }

    public function updateRules(Request $request)
    {
        $rulesData = $request->input('rules', []);

        foreach ($rulesData as $id => $data) {
            $rule = XpRule::find($id);
            if ($rule) {
                $rule->update([
                    'xp_amount' => (int) ($data['xp_amount'] ?? $rule->xp_amount),
                    'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : false,
                ]);
            }
        }

        return back()->with('success', 'Aturan perolehan XP berhasil diperbarui!');
    }
}
