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
        'avatar',
        'phone',
        'bio',
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
        ];
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
     * Dokumen trip milik pengguna.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TripDocument::class);
    }
}
