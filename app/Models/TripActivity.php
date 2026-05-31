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
        'location_name',
        'location_url',
        'estimated_cost',
        'category',
        'sort_order',
        'is_completed',
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

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Ikon emoji berdasarkan kategori aktivitas.
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'transportasi' => '🚗',
            'penginapan'   => '🏨',
            'makanan'      => '🍽️',
            'wisata'       => '🏖️',
            'belanja'      => '🛍️',
            'hiburan'      => '🎭',
            'olahraga'     => '⛷️',
            'budaya'       => '🏛️',
            'alam'         => '🌿',
            'foto'         => '📸',
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
