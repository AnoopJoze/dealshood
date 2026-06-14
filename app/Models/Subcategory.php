<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use HasFactory, SoftDeletes;
    //
    protected $fillable = [
        'name',
        'category_id',
        'slug',
        'is_active',
        'sort_order',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }
}
