<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'status'];

    /**
     * Relationship: A category has many sub-categories
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
