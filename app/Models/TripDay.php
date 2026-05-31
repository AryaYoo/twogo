<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model TripDay — Hari dalam sebuah trip.
 *
 * @property int $id
 * @property int $trip_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $day_number
 * @property string|null $notes
 */
class TripDay extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'date',
        'day_number',
        'notes',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Trip yang memiliki hari ini.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Aktivitas pada hari ini, diurutkan berdasarkan sort_order.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(TripActivity::class)->orderBy('sort_order');
    }

    /**
     * Dokumen yang terkait dengan hari ini.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TripDocument::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Methods                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Filter aktivitas berdasarkan sesi (pagi, siang, malam, dll).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, TripActivity>
     */
    public function activitiesBySession(string $session): Collection
    {
        return $this->activities()->where('session', $session)->get();
    }

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Tanggal dalam format Bahasa Indonesia (contoh: "Senin, 1 Juni 2026").
     */
    public function getFormattedDateAttribute(): string
    {
        $days = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        $months = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $dayName   = $days[$this->date->format('l')] ?? $this->date->format('l');
        $day       = $this->date->format('j');
        $monthName = $months[(int) $this->date->format('n')] ?? $this->date->format('F');
        $year      = $this->date->format('Y');

        return "{$dayName}, {$day} {$monthName} {$year}";
    }
}
