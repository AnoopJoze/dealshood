<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];
    public function parent()
{
    return $this->belongsTo(Locality::class, 'parent_id');
}
}
