<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'day',
        'date',
        'start_time',
        'end_time',
        'user_id',
        'status',
    ];
}
