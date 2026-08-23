<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingShowcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_badge',
        'title',
        'description',
        'bullet_points',
        'badge_color',
        'mockup_type',
        'order',
        'is_active',
    ];

    protected $casts = [
        'bullet_points' => 'array',
        'order'         => 'integer',
        'is_active'     => 'boolean',
    ];
}
