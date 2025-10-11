<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'status'];

    /**
     * Relationship: A sub-category belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
