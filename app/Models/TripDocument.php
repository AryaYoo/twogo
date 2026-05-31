<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TripDocument — Dokumen (foto, catatan, dll.) dalam trip.
 *
 * @property int $id
 * @property int $trip_id
 * @property int $user_id
 * @property int|null $trip_day_id
 * @property string $type
 * @property string|null $file_path
 * @property string|null $caption
 * @property string|null $content
 */
class TripDocument extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'user_id',
        'trip_day_id',
        'type',
        'file_path',
        'caption',
        'content',
    ];

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
     * Pengguna yang mengunggah dokumen.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hari trip terkait (opsional).
     */
    public function day(): BelongsTo
    {
        return $this->belongsTo(TripDay::class, 'trip_day_id');
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Scope: hanya dokumen bertipe foto.
     */
    public function scopePhotos(Builder $query): Builder
    {
        return $query->where('type', 'photo');
    }

    /**
     * Scope: hanya dokumen bertipe catatan.
     */
    public function scopeNotes(Builder $query): Builder
    {
        return $query->where('type', 'note');
    }
}
