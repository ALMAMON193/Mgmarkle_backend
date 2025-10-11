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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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
}
