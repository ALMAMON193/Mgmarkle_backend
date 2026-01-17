<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'event_booking_id',
        'amount',
        'transaction_id',
        'status',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Payment belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Payment belongs to an event booking
     */
    public function booking()
    {
        return $this->belongsTo(EventBooking::class, 'event_booking_id');
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }
}
