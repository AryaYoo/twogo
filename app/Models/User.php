<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — Pengguna aplikasi TwoGo.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property string|null $phone
 * @property string|null $bio
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'phone',
        'bio',
        'xp',
        'is_admin',
        'status',
        'account_tier',
        'ai_itinerary_count',
        'ai_itinerary_reset_at',
        'last_login_at',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'last_login_at' => 'datetime',
            'ai_itinerary_count' => 'integer',
            'ai_itinerary_reset_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isPremium(): bool
    {
        return $this->account_tier === 'premium';
    }

    public function isEarlyAccess(): bool
    {
        return in_array($this->account_tier, ['early_access', 'premium']);
    }

    /**
     * Cek dan sinkronkan kuota AI jika durasi reset (2 jam) telah tercapai.
     */
    public function checkAndRefreshAiQuota(): void
    {
        if ($this->ai_itinerary_reset_at && now()->greaterThanOrEqualTo($this->ai_itinerary_reset_at)) {
            $this->update([
                'ai_itinerary_count' => 0,
                'ai_itinerary_reset_at' => null,
            ]);
            $this->refresh();
        }
    }

    /**
     * Hitung sisa kuota AI (Maksimal 2).
     */
    public function getAiQuotaRemainingAttribute(): int
    {
        $this->checkAndRefreshAiQuota();
        return max(0, 2 - ($this->ai_itinerary_count ?? 0));
    }

    public function canUseAiItinerary(): bool
    {
        if (!$this->isEarlyAccess()) {
            return false;
        }

        $this->checkAndRefreshAiQuota();

        return $this->ai_itinerary_count < 2;
    }

    /**
     * Data badge dan deskripsi untuk account tier pengguna.
     */
    public function getTierBadgeAttribute(): array
    {
        return match ($this->account_tier) {
            'premium' => [
                'name'        => 'Premium',
                'icon'        => '👑',
                'badge_class' => 'bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]',
                'label'       => 'Member Premium',
                'description' => 'Akses prioritas tak terbatas ke seluruh fitur TwoGo.',
                'tag'         => 'PRO',
                'tag_class'   => 'bg-[#1A1A2E] text-[#FFE156]',
            ],
            'early_access' => [
                'name'        => 'Early Access',
                'icon'        => '🚀',
                'badge_class' => 'bg-[#4361EE] text-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]',
                'label'       => 'Tester Early Access',
                'description' => 'Akses perdana ke fitur eksperimental (seperti AI Itinerary).',
                'tag'         => 'BETA',
                'tag_class'   => 'bg-[#4361EE] text-white',
            ],
            default => [
                'name'        => 'Standard',
                'icon'        => '🌱',
                'badge_class' => 'bg-slate-100 text-slate-700 border border-[#1A1A2E]',
                'label'       => 'Standard Member',
                'description' => 'Akun reguler TwoGo.',
                'tag'         => 'FREE',
                'tag_class'   => 'bg-slate-200 text-slate-700',
            ],
        };
    }

    public function isActive(): bool
    {
        return $this->status === 'active' || empty($this->status);
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    /**
     * URL Avatar pengguna.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        return str_starts_with($this->avatar, 'http')
            ? $this->avatar
            : \Illuminate\Support\Facades\Storage::url($this->avatar);
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Trip yang diikuti pengguna (melalui tabel pivot trip_members).
     */
    public function trips(): BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_members')
                    ->withPivot('role', 'joined_at');
    }

    /**
     * Trip yang dibuat/dimiliki oleh pengguna.
     */
    public function ownedTrips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Pengeluaran yang dibayar oleh pengguna.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'paid_by');
    }

    /**
     * Relasi pertemanan yang dimiliki pengguna.
     */
    public function friendships(): HasMany
    {
        return $this->hasMany(Friendship::class);
    }

    /**
     * Mendapatkan daftar teman yang sudah diterima (accepted).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function friends(): Collection
    {
        $friendIds = Friendship::where(function ($query) {
            $query->where('user_id', $this->id)
                  ->orWhere('friend_id', $this->id);
        })
        ->where('status', 'accepted')
        ->get()
        ->map(function (Friendship $friendship) {
            return $friendship->user_id === $this->id
                ? $friendship->friend_id
                : $friendship->user_id;
        })
        ->unique();

        return User::whereIn('id', $friendIds)->get();
    }

    /**
     * Item wishlist yang ditambahkan pengguna.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class, 'added_by');
    }

    /**
     * Pesan obrolan trip yang dikirim pengguna.
     */
    public function tripMessages(): HasMany
    {
        return $this->hasMany(TripMessage::class);
    }

    /**
     * Dokumen trip milik pengguna.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TripDocument::class);
    }
}
