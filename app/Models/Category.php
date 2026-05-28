<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];
    //
    public function subcategories()
{
    return $this->hasMany(Subcategory::class);
}
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }
}
