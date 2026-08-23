<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'bg_color',
        'text_color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];
}
