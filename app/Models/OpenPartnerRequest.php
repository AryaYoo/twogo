<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model OpenPartnerRequest — Permohonan gabung partner ke Trip Open Partner.
 *
 * @property int $id
 * @property int $trip_id
 * @property int $requester_id
 * @property string|null $message
 * @property string $status
 */
class OpenPartnerRequest extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'requester_id',
        'message',
        'status',
    ];

    /**
     * Trip yang dimohon.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Pengguna yang mengajukan permohonan.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
