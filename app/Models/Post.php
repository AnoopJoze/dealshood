<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category_id',
        'subcategory_id',
        'locality_id',
        'country',
        'state',
        'city',
        'location',
        'latitude',
        'longitude',
        'google_map_url',
        'featured_image',
        'meta_title',
        'meta_description',
        'keywords',
        'status',
        'is_featured',
        'is_active',
        'published_at',
        'expiry_date',
        'views',
    ];

    // Relationships (important for your earlier error style issues)

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function locality()
{
    return $this->belongsTo(Locality::class);
}
}
