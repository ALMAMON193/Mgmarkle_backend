<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'category_id',
        'sub_category_id',
        'birth_date',
        'gender',
        'about_us',
        'profile_picture',
        'affiliated_offer',
        'topic_offer',
    ];

    /**
     * Automatically cast topic_offer JSON to array
     */
    protected $casts = [
        'topic_offer' => 'array',
        'birth_date' => 'date',
    ];

    /**
     * Profile belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Accessor for topic_offer to automatically decode JSON.
     */
    public function getTopicOfferAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Mutator for topic_offer to automatically encode JSON.
     */
    public function setTopicOfferAttribute($value)
    {
        $this->attributes['topic_offer'] = $value ? json_encode($value) : null;
    }
}
