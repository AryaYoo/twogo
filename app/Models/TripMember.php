<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Model Pivot TripMember — Keanggotaan pengguna dalam trip.
 *
 * Maksimal 2 anggota per trip.
 *
 * @property int $trip_id
 * @property int $user_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $joined_at
 */
class TripMember extends Pivot
{
    /**
     * Nama tabel yang digunakan.
     *
     * @var string
     */
    protected $table = 'trip_members';

    /**
     * Nonaktifkan timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'user_id',
        'role',
        'joined_at',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
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
     * Pengguna terkait.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
