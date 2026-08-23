<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model XpRule — Aturan perolehan XP dinamis.
 */
class XpRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'xp_amount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'xp_amount' => 'integer',
        'is_active' => 'boolean',
    ];
}
