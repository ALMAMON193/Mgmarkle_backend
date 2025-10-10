<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Otp extends Model
{
    protected $fillable = ['user_id','otp', 'expires_at', 'is_verified', 'verified_at','purpose'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
