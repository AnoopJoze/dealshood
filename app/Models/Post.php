<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posts');
        $this->addMediaCollection('videos');
    }

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'subcategory_id',
        'locality_id',
        'user_id',
        'status',
        'is_featured',
        'is_active',
        'views',
        'expiry_date',
        'published_at',
        // Location
        'country',
        'state',
        'city',
        'location',
        'latitude',
        'longitude',
        'google_map_url',
        // Media
        'video_url',
        // SEO
        'meta_title',
        'meta_description',
        'keywords',
    ];

    /**
     * Sensitive location / content fields are encrypted at rest.
     *
     * ⚠  These columns must be TEXT in the database (see migration).
     *     They cannot be used in WHERE / LIKE / ORDER BY queries.
     *
     * Fields NOT encrypted (need to be searchable / sortable):
     *   title, slug, status, is_featured, is_active, views,
     *   category_id, subcategory_id, locality_id, user_id,
     *   expiry_date, published_at, created_at, updated_at
     */
    protected $casts = [
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
        'expiry_date'  => 'date',

        // Location PII — encrypted at rest
        'latitude'       => 'encrypted',
        'longitude'      => 'encrypted',
        'google_map_url' => 'encrypted',

        // Semi-sensitive location descriptors — encrypted at rest
        'country'  => 'encrypted',
        'state'    => 'encrypted',
        'city'     => 'encrypted',
        'location' => 'encrypted',

        // Content — encrypted at rest
        'description' => 'encrypted',

        // SEO — encrypted at rest (can contain business details)
        'meta_title'       => 'encrypted',
        'meta_description' => 'encrypted',
        'keywords'         => 'encrypted',
    ];

    /* ── URL accessor ──────────────────────────────── */
    public function getUrlAttribute(): string
    {
        return route('post-details', [
            'locality'    => $this->locality?->slug    ?? 'na',
            'category'    => $this->category?->slug    ?? 'na',
            'subcategory' => $this->subcategory?->slug ?? 'na',
            'slug'        => $this->slug,
        ]);
    }

    /* ── Relations ─────────────────────────────────── */
    public function category():    \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function locality():    \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function user():        \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likesData():   \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function viewsData():   \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function sharesData():  \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostShare::class);
    }
}