<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TripInvitation — Undangan untuk bergabung ke trip.
 *
 * @property int $id
 * @property int $trip_id
 * @property int $invited_by
 * @property int|null $invited_user_id
 * @property string $status
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 */
class TripInvitation extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'invited_by',
        'invited_user_id',
        'status',
        'token',
        'expires_at',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Trip terkait.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Pengguna yang mengirim undangan.
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Pengguna yang diundang.
     */
    public function invitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    /* ------------------------------------------------------------------ */
    /*  Methods                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Cek apakah undangan sudah kedaluwarsa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Scope: undangan yang masih aktif (pending & belum kedaluwarsa).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'pending')
                     ->where('expires_at', '>', now());
    }
}
