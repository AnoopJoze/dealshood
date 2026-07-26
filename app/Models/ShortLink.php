<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShortLink extends Model
{
    protected $fillable = ['code', 'url', 'clicks'];

    /**
     * Reuse an existing short link for this exact URL, or create one.
     */
    public static function getOrCreateFor(string $url): self
    {
        return static::firstOrCreate(
            ['url' => $url],
            ['code' => static::generateUniqueCode()]
        );
    }

    private static function generateUniqueCode(): string
    {
        do {
            $code = Str::random(6);
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function getShortUrlAttribute(): string
    {
        return url('/s/' . $this->code);
    }
}
