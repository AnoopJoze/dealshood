<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locality extends Model
{
    use HasFactory, SoftDeletes;
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
    return $this->hasMany(Locality::class, 'parent_id')->orderBy('name');
}
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Hierarchy helpers
    |--------------------------------------------------------------------------
    */

    /**
     * This locality's id plus every descendant id (children, grandchildren, etc).
     * Selecting a country returns the country + all states + all cities + areas under it.
     */
    public function getAllDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }

        return $ids;
    }

    /**
     * Resolve a slug straight to its full id tree. Returns [] if not found.
     */
    public static function descendantIdsForSlug(?string $slug): array
    {
        if (! $slug) {
            return [];
        }

        $locality = static::where('slug', $slug)->first();

        return $locality ? $locality->getAllDescendantIds() : [];
    }
    /**
 * Returns only localities that have at least one published post,
 * either directly or anywhere in their subtree (so a country/state
 * still shows up if any of its descendants have posts, even with
 * zero posts tagged to it directly).
 */
public static function withPostsTree()
{
    $all = static::where('is_active', true)
        ->get(['id', 'name', 'slug', 'type', 'parent_id']);

    $directIds = \App\Models\Post::where('status', 'published')
        ->whereNotNull('locality_id')
        ->distinct()
        ->pluck('locality_id');

    $byId = $all->keyBy('id');
    $relevantIds = [];

    foreach ($directIds as $id) {
        $loc = $byId->get($id);
        while ($loc) {
            $relevantIds[$loc->id] = true;
            $loc = $loc->parent_id ? $byId->get($loc->parent_id) : null;
        }
    }

    return $all->whereIn('id', array_keys($relevantIds))
        ->sortBy('name')
        ->values();
}
}
