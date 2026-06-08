<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripLike extends Model
{
    protected $fillable = ['user_id', 'trip_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
