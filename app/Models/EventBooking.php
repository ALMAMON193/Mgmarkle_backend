<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'leader_id',
        'booking_type',
        'amount',
        'status',
        'starts_at',
        'ends_at',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event being booked.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
