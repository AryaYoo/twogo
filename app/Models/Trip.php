<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

/**
 * Model Trip — Perjalanan/itinerary pada aplikasi TwoGo.
 *
 * Setiap trip maksimal 2 anggota. Mata uang: IDR.
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string $destination
 * @property string|null $cover_image
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property float $total_budget
 * @property string $invite_code
 * @property string $status
 */
class Trip extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'destination',
        'cover_image',
        'start_date',
        'end_date',
        'total_budget',
        'invite_code',
        'status',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date'   => 'date',
            'end_date'     => 'date',
            'total_budget' => 'decimal:2',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Boot                                                               */
    /* ------------------------------------------------------------------ */

    /**
     * Boot model — otomatis buat invite_code saat membuat trip baru.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Trip $trip) {
            if (empty($trip->invite_code)) {
                $trip->invite_code = static::generateInviteCode();
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Pembuat/pemilik trip.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Anggota trip (melalui tabel pivot trip_members).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'trip_members')
                    ->withPivot('role', 'joined_at');
    }

    /**
     * Hari-hari dalam trip, diurutkan berdasarkan day_number.
     */
    public function days(): HasMany
    {
        return $this->hasMany(TripDay::class)->orderBy('day_number');
    }

    /**
     * Semua aktivitas dalam trip (melalui TripDay).
     */
    public function activities(): HasManyThrough
    {
        return $this->hasManyThrough(TripActivity::class, TripDay::class);
    }

    /**
     * Pengeluaran dalam trip.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Item wishlist dalam trip.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Dokumen dalam trip.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TripDocument::class);
    }

    /**
     * Undangan trip.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TripInvitation::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Methods                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Cek apakah trip sudah penuh (maks 2 anggota).
     */
    public function isFull(): bool
    {
        return $this->members()->count() >= 2;
    }

    /**
     * Cek apakah user tertentu adalah pemilik trip.
     */
    public function isOwner(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Cek apakah user tertentu adalah anggota trip.
     */
    public function isMember(User $user): bool
    {
        return $this->members()->where('users.id', $user->id)->exists();
    }

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Total pengeluaran trip (dalam IDR).
     */
    public function getTotalSpentAttribute(): float
    {
        return (float) $this->expenses()->sum('amount');
    }

    /**
     * Sisa anggaran trip (dalam IDR).
     */
    public function getRemainingBudgetAttribute(): float
    {
        return (float) $this->total_budget - $this->total_spent;
    }

    /* ------------------------------------------------------------------ */
    /*  Static Helpers                                                     */
    /* ------------------------------------------------------------------ */

    /**
     * Generate kode undangan 6 karakter alfanumerik (huruf besar).
     */
    public static function generateInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (static::where('invite_code', $code)->exists());

        return $code;
    }
}
