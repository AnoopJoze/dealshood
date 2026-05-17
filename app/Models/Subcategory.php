<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    //
    protected $fillable = [
        'name',
        'category_id',
        'slug',
        'is_active',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}
}
