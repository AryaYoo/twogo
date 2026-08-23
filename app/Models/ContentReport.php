<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ContentReport — Laporan moderasi konten itinerary.
 */
class ContentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'reporter_id',
        'reason',
        'details',
        'status',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
