<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

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
