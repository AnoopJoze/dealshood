<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone',
        'whatsapp_number',
        'location',
        'about_me',
        'company_name',
        'address',
        'website',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Sensitive PII fields are encrypted at rest using Laravel's built-in
     * AES-256-CBC encryption (keyed with APP_KEY).
     * Decryption is automatic on attribute access — no controller changes needed.
     *
     * ⚠  These columns must be TEXT in the database (see migration).
     *     They cannot be used in WHERE / LIKE / ORDER BY queries.
     */
    protected $casts = [
        // Auth
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',

        // PII — encrypted at rest
        'phone'           => 'encrypted',
        'whatsapp_number' => 'encrypted',
        'address'         => 'encrypted',
        'latitude'        => 'encrypted',
        'longitude'       => 'encrypted',

        // Semi-sensitive — encrypted at rest
        'location'     => 'encrypted',
        'company_name' => 'encrypted',
        'about_me'     => 'encrypted',
        'website'      => 'encrypted',
    ];

    /* ── Relations ─────────────────────────────────── */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}