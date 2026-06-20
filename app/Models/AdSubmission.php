<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AdSubmission extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name', 'email', 'phone', 'whatsapp',
        'title', 'category_id', 'locality_id',
        'description', 'company_name', 'location',
        'offer_percentage', 'expiry_date',
        'status', 'admin_notes',
    ];

    protected $casts = [
        'expiry_date'      => 'date',
        'offer_percentage' => 'decimal:2',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'approved' => ['bg-success-subtle', 'text-success'],
            'rejected' => ['bg-danger-subtle', 'text-danger'],
            default    => ['bg-warning-subtle', 'text-warning'],
        };
    }
}