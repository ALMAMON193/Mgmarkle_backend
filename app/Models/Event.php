<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'date',
        'start_time',
        'end_time',
        'location',
        'description',
        'visibility',
        'zoom_session_id',
        'image',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(EventBooking::class);
    }
}
