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
        'video',
        'video_url',
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
public function likesData()
{
    return $this->hasMany(PostLike::class);
}

public function viewsData()
{
    return $this->hasMany(PostView::class);
}

public function sharesData()
{
    return $this->hasMany(PostShare::class);
}

public function getUrlAttribute()
{
    return route('post-details', [

        'locality' => $this->locality?->slug,

        'category' => $this->category?->slug,

        'subcategory' => $this->subcategory?->slug,

        'slug' => $this->slug,

    ]);
}
}
