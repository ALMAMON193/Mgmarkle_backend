<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderBooking extends Model
{
    protected $fillable = [
        'user_id',
        'leader_id',
        'amount',
        'status',
        'starts_at',
        'ends_at',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
}
