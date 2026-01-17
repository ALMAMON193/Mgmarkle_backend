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
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
