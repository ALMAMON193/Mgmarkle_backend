<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'comment',
    ];

    /**
     * The user that this rating belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
