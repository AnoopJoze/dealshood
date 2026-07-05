<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRating extends Model
{
    protected $fillable = ['post_id', 'user_id', 'ip_address', 'session_id', 'rating'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}