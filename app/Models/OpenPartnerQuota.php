<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model OpenPartnerQuota — Pelacakan kuota Open Partner bulanan per user (Maks 2x/bulan).
 *
 * @property int $id
 * @property int $user_id
 * @property string $month
 * @property int $used_count
 */
class OpenPartnerQuota extends Model
{
    use HasFactory;

    public const MAX_QUOTA_PER_MONTH = 2;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'month',
        'used_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dapatkan sisa kuota user untuk bulan ini.
     */
    public static function getRemainingQuota(User|int $user): int
    {
        $userId = $user instanceof User ? $user->id : $user;
        $currentMonth = Carbon::now()->format('Y-m');

        $record = static::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->first();

        $used = $record ? $record->used_count : 0;
        return max(0, self::MAX_QUOTA_PER_MONTH - $used);
    }

    /**
     * Dapatkan jumlah kuota terpakai user untuk bulan ini.
     */
    public static function getUsedQuota(User|int $user): int
    {
        $userId = $user instanceof User ? $user->id : $user;
        $currentMonth = Carbon::now()->format('Y-m');

        $record = static::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->first();

        return $record ? $record->used_count : 0;
    }

    /**
     * Gunakan 1 kuota open partner untuk user.
     */
    public static function useQuota(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        $currentMonth = Carbon::now()->format('Y-m');

        $record = static::firstOrCreate(
            ['user_id' => $userId, 'month' => $currentMonth],
            ['used_count' => 0]
        );

        if ($record->used_count >= self::MAX_QUOTA_PER_MONTH) {
            return false;
        }

        $record->increment('used_count');
        return true;
    }
}
