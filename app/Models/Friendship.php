<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Friendship — Relasi pertemanan antar pengguna.
 *
 * @property int $id
 * @property int $user_id
 * @property int $friend_id
 * @property string $status
 */
class Friendship extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Pengguna yang mengirim permintaan pertemanan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pengguna yang menerima permintaan pertemanan.
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Scope: hanya pertemanan yang sudah diterima.
     */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope: hanya pertemanan yang masih pending.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
