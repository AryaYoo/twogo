<?php

namespace App\Services;

use App\Models\User;
use App\Models\XpLog;
use Illuminate\Support\Collection;

class GamificationService
{
    /**
     * XP required per level step (each level = 100 XP).
     */
    const XP_PER_LEVEL = 100;

    /**
     * Tier definitions: ordered from highest to lowest.
     */
    const TIERS = [
        [
            'name'       => 'TwoGo-er Legend',
            'min'        => 2001,
            'max'        => null,
            'emoji'      => '👑',
            'card_bg'    => '#FFE156',
            'card_text'  => '#1A1A2E',
            'bar_color'  => '#F59E0B',
            'badge_class'=> 'bg-[#FFE156] text-[#1A1A2E]',
        ],
        [
            'name'       => 'Traveler Sejati',
            'min'        => 1001,
            'max'        => 2000,
            'emoji'      => '🌟',
            'card_bg'    => '#FF6B9D',
            'card_text'  => '#FFFFFF',
            'bar_color'  => '#EC4899',
            'badge_class'=> 'bg-[#FF6B9D] text-white',
        ],
        [
            'name'       => 'Petualang',
            'min'        => 501,
            'max'        => 1000,
            'emoji'      => '🧗',
            'card_bg'    => '#4361EE',
            'card_text'  => '#FFFFFF',
            'bar_color'  => '#4361EE',
            'badge_class'=> 'bg-[#4361EE] text-white',
        ],
        [
            'name'       => 'Penjelajah',
            'min'        => 201,
            'max'        => 500,
            'emoji'      => '🗺️',
            'card_bg'    => '#00D4AA',
            'card_text'  => '#1A1A2E',
            'bar_color'  => '#00D4AA',
            'badge_class'=> 'bg-[#00D4AA] text-[#1A1A2E]',
        ],
        [
            'name'       => 'Pelancong Baru',
            'min'        => 0,
            'max'        => 200,
            'emoji'      => '🎒',
            'card_bg'    => '#E2E8F0',
            'card_text'  => '#1A1A2E',
            'bar_color'  => '#94A3B8',
            'badge_class'=> 'bg-[#E2E8F0] text-[#1A1A2E]',
        ],
    ];

    /**
     * XP rewards for each event type.
     */
    const REWARDS = [
        'trip_created'         => 50,
        'activity_completed'   => 10,
        'trip_completed'       => 100,
        'trip_liked'           => 20,
        'trip_cloned'          => 50,
        'friend_added'         => 15,
        'partner_bonus'        => 50,
    ];

    /**
     * Get the tier for a given XP amount.
     */
    public static function getTier(int $xp): array
    {
        foreach (self::TIERS as $tier) {
            if ($xp >= $tier['min']) {
                return $tier;
            }
        }
        return self::TIERS[array_key_last(self::TIERS)];
    }

    /**
     * Get full level info for a given XP value.
     */
    public static function getLevelInfo(int $xp): array
    {
        $tier        = self::getTier($xp);
        $tierMin     = $tier['min'];
        $tierMax     = $tier['max'];
        $level       = (int) floor($xp / self::XP_PER_LEVEL) + 1;
        $xpInLevel   = $xp % self::XP_PER_LEVEL;
        $percent     = min(100, (int) (($xpInLevel / self::XP_PER_LEVEL) * 100));

        // Progress within current tier
        $tierXp      = $xp - $tierMin;
        $tierRange   = $tierMax !== null ? ($tierMax - $tierMin) : 0;
        $tierPercent = $tierMax !== null ? min(100, (int) (($tierXp / $tierRange) * 100)) : 100;
        $xpToNextTier = $tierMax !== null ? max(0, $tierMax - $xp + 1) : 0;

        return compact('tier', 'level', 'xpInLevel', 'percent', 'tierPercent', 'xpToNextTier', 'tierMin', 'tierMax');
    }

    /**
     * Get all tiers definition list (for mission/detail display).
     */
    public static function getAllTiers(): array
    {
        return self::TIERS;
    }

    /**
     * Award XP to a user and log the event.
     * Returns true if tier changed (for popup notification).
     */
    public static function awardXp(
        User    $user,
        string  $sourceType,
        ?int    $sourceId  = null,
        ?User   $partner   = null,
        ?string $description = null
    ): bool {
        $amount = self::REWARDS[$sourceType] ?? 0;
        if ($amount === 0) return false;

        $user->refresh();
        $oldXp = $user->xp;
        $newXp = $oldXp + $amount;

        XpLog::create([
            'user_id'     => $user->id,
            'partner_id'  => $partner?->id,
            'amount'      => $amount,
            'source_type' => $sourceType,
            'source_id'   => $sourceId,
            'description' => $description ?? self::getDefaultDescription($sourceType),
        ]);

        $user->update(['xp' => $newXp]);

        // Check for tier change
        $oldTier = self::getTier($oldXp)['name'];
        $newTier = self::getTier($newXp)['name'];

        $tierChanged = ($oldTier !== $newTier);
        if ($tierChanged) {
            // Store in session for popup on next page load
            $tierData = self::getTier($newXp);
            session()->flash('tier_up', [
                'from'  => $oldTier,
                'to'    => $newTier,
                'emoji' => $tierData['emoji'],
                'bg'    => $tierData['card_bg'],
                'text'  => $tierData['card_text'],
            ]);
        }

        return $tierChanged;
    }

    /**
     * Get best partners for a user (people they gained XP with).
     */
    public static function getBestPartners(User $user): Collection
    {
        return XpLog::query()
            ->where('user_id', $user->id)
            ->whereNotNull('partner_id')
            ->selectRaw('partner_id, SUM(amount) as shared_xp')
            ->groupBy('partner_id')
            ->orderByDesc('shared_xp')
            ->with('partner')
            ->get()
            ->map(fn ($log) => [
                'user'       => $log->partner,
                'shared_xp'  => $log->shared_xp,
            ])
            ->filter(fn ($item) => $item['user'] !== null)
            ->values();
    }

    /**
     * Get mission completion status for a user.
     */
    public static function getMissions(User $user): array
    {
        $logs = XpLog::where('user_id', $user->id)->get();

        return [
            [
                'title'       => 'Buat Trip Pertama',
                'description' => 'Rencanakan perjalananmu pertama',
                'xp'          => 50,
                'emoji'       => '✈️',
                'done'        => $logs->where('source_type', 'trip_created')->count() >= 1,
                'progress'    => min(1, $logs->where('source_type', 'trip_created')->count()),
                'target'      => 1,
            ],
            [
                'title'       => 'Buat 5 Trip',
                'description' => 'Jadilah pelancong aktif',
                'xp'          => 50 * 5,
                'emoji'       => '🗓️',
                'done'        => $logs->where('source_type', 'trip_created')->count() >= 5,
                'progress'    => min(5, $logs->where('source_type', 'trip_created')->count()),
                'target'      => 5,
            ],
            [
                'title'       => 'Selesaikan 10 Kegiatan',
                'description' => 'Tuntaskan 10 kegiatan dalam itinerary',
                'xp'          => 10 * 10,
                'emoji'       => '✅',
                'done'        => $logs->where('source_type', 'activity_completed')->count() >= 10,
                'progress'    => min(10, $logs->where('source_type', 'activity_completed')->count()),
                'target'      => 10,
            ],
            [
                'title'       => 'Selesaikan Trip',
                'description' => 'Tandai trip pertamamu sebagai selesai',
                'xp'          => 100,
                'emoji'       => '🏁',
                'done'        => $logs->where('source_type', 'trip_completed')->count() >= 1,
                'progress'    => min(1, $logs->where('source_type', 'trip_completed')->count()),
                'target'      => 1,
            ],
            [
                'title'       => 'Dapat 5 Likes',
                'description' => 'Inspirasi 5 pengguna lain dengan tripmu',
                'xp'          => 20 * 5,
                'emoji'       => '❤️',
                'done'        => $logs->where('source_type', 'trip_liked')->count() >= 5,
                'progress'    => min(5, $logs->where('source_type', 'trip_liked')->count()),
                'target'      => 5,
            ],
            [
                'title'       => 'Itinerary Disalin Orang Lain',
                'description' => 'Itinerarimu dijadikan inspirasi orang lain',
                'xp'          => 50,
                'emoji'       => '📋',
                'done'        => $logs->where('source_type', 'trip_cloned')->count() >= 1,
                'progress'    => min(1, $logs->where('source_type', 'trip_cloned')->count()),
                'target'      => 1,
            ],
            [
                'title'       => 'Tambah 3 Teman',
                'description' => 'Bangun jaringan perjalananmu',
                'xp'          => 15 * 3,
                'emoji'       => '👫',
                'done'        => $logs->where('source_type', 'friend_added')->count() >= 3,
                'progress'    => min(3, $logs->where('source_type', 'friend_added')->count()),
                'target'      => 3,
            ],
            [
                'title'       => 'Trip Bareng Partner',
                'description' => 'Selesaikan trip bersama partner untuk bonus XP',
                'xp'          => 50,
                'emoji'       => '👫',
                'done'        => $logs->where('source_type', 'partner_bonus')->count() >= 1,
                'progress'    => min(1, $logs->where('source_type', 'partner_bonus')->count()),
                'target'      => 1,
            ],
        ];
    }

    private static function getDefaultDescription(string $sourceType): string
    {
        return match ($sourceType) {
            'trip_created'       => 'Membuat trip baru',
            'activity_completed' => 'Menyelesaikan kegiatan',
            'trip_completed'     => 'Trip selesai',
            'trip_liked'         => 'Trip mendapatkan like',
            'trip_cloned'        => 'Itinerary disalin pengguna lain',
            'friend_added'       => 'Menambahkan teman baru',
            'partner_bonus'      => 'Bonus trip berdua',
            default              => 'XP diperoleh',
        };
    }
}
