<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'is_verified',
        'email_verified_at',
        'verified_at',
        'reset_password_token',
        'reset_password_token_expire_at',
        'phone_number',
        'otp',
        'purpose',
        'expires_at',
        'stripe_customer_id',
        'session_price',

        // subscription
        'is_subscribed',
        'subscription_id',
        'subscription_plan',
        'subscription_start_at',
        'subscription_end_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'reset_password_token_expire_at' => 'datetime',

        // subscription
        'subscription_start_at' => 'datetime',
        'subscription_end_at' => 'datetime',

        'password' => 'hashed',
        'is_subscribed' => 'boolean',
    ];

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function latestOtp()
    {
        return $this->hasOne(Otp::class)->latestOfMany();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    //  bookings for events
    public function eventBookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    //  payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // available slots
    public function availableSlots()
    {
        return $this->hasMany(Availability::class);
    }

    public function getProfileSetupAttribute(): bool
    {
        $profile = $this->profile;

        if (! $profile) {
            return false;
        }

        $requiredFields = [
            'birth_date',
            'gender',
            'about_us',
            'profile_picture',
            'topic_offer',
        ];

        foreach ($requiredFields as $field) {
            if ($field === 'topic_offer' && empty($profile->$field)) {
                return false;
            }
            if ($field !== 'topic_offer' && (is_null($profile->$field) || $profile->$field === '')) {
                return false;
            }
        }

        return true;
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Average rating of this user.
     */
    public function averageRating(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    /**
     * Total number of ratings.
     */
    public function ratingsCount(): int
    {
        return $this->ratings()->count();
    }
}
