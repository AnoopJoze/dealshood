<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function parent()
    {
        return $this->belongsTo(Locality::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Locality::class, 'parent_id');
    }
}