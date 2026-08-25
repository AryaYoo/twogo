<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TripActivity — Aktivitas dalam satu hari trip.
 *
 * @property int $id
 * @property int $trip_day_id
 * @property string $title
 * @property string|null $description
 * @property string $session
 * @property string|null $location_name
 * @property string|null $location_url
 * @property float $estimated_cost
 * @property string $category
 * @property int $sort_order
 * @property bool $is_completed
 */
class TripActivity extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_day_id',
        'title',
        'description',
        'session',
        'start_time',
        'end_time',
        'location_name',
        'location_url',
        'estimated_cost',
        'actual_cost',
        'photo',
        'category',
        'sort_order',
        'is_completed',
        'is_public',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'is_completed'   => 'boolean',
            'is_public'      => 'boolean',
            'notified_start' => 'boolean',
            'notified_end'   => 'boolean',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Hari trip yang memiliki aktivitas ini.
     */
    public function day(): BelongsTo
    {
        return $this->belongsTo(TripDay::class, 'trip_day_id');
    }

    /**
     * Trip yang memiliki aktivitas ini (via TripDay).
     */
    public function trip()
    {
        return $this->hasOneThrough(
            \App\Models\Trip::class,
            TripDay::class,
            'id',       // FK di trip_days
            'id',       // FK di trips
            'trip_day_id', // local key di trip_activities
            'trip_id'   // local key di trip_days
        );
    }

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Ikon emoji berdasarkan kategori aktivitas.
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'wisata'       => '🏖️',
            'kuliner'      => '🍜',
            'transportasi' => '🚗',
            'akomodasi'    => '🏨',
            'belanja'      => '🛍️',
            default        => '📌',
        };
    }

    /**
     * Ikon emoji berdasarkan sesi waktu.
     */
    public function getSessionIconAttribute(): string
    {
        return match ($this->session) {
            'pagi'   => '🌅',
            'siang'  => '☀️',
            'sore'   => '🌇',
            'malam'  => '🌙',
            default  => '🕐',
        };
    }
}
