<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model WishlistItem — Item wishlist dalam trip.
 *
 * @property int $id
 * @property int $trip_id
 * @property int $added_by
 * @property string $name
 * @property string|null $description
 * @property string $category
 * @property string|null $location_name
 * @property string|null $location_url
 * @property string|null $image_url
 * @property float $estimated_cost
 * @property string $priority
 * @property bool $is_added_to_itinerary
 * @property array $votes
 */
class WishlistItem extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'added_by',
        'name',
        'description',
        'category',
        'location_name',
        'location_url',
        'image_url',
        'estimated_cost',
        'priority',
        'is_added_to_itinerary',
        'votes',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estimated_cost'        => 'decimal:2',
            'is_added_to_itinerary' => 'boolean',
            'votes'                 => 'array',
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
     * Pengguna yang menambahkan item ini.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /* ------------------------------------------------------------------ */
    /*  Methods                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Cek apakah user tertentu sudah memberikan vote.
     */
    public function hasVoted(User $user): bool
    {
        return in_array($user->id, $this->votes ?? []);
    }

    /**
     * Toggle vote untuk user tertentu (tambah jika belum, hapus jika sudah).
     */
    public function toggleVote(User $user): void
    {
        $votes = $this->votes ?? [];

        if ($this->hasVoted($user)) {
            $votes = array_values(array_filter($votes, fn (int $id) => $id !== $user->id));
        } else {
            $votes[] = $user->id;
        }

        $this->votes = $votes;
        $this->save();
    }

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Jumlah vote pada item ini.
     */
    public function getVoteCountAttribute(): int
    {
        return count($this->votes ?? []);
    }

    /**
     * Warna kelas CSS berdasarkan prioritas.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'tinggi' => 'text-red-600 bg-red-100',
            'sedang' => 'text-yellow-600 bg-yellow-100',
            'rendah' => 'text-green-600 bg-green-100',
            default  => 'text-gray-600 bg-gray-100',
        };
    }
}
