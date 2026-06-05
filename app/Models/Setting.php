<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // ── Get a setting value by key ──────────────────────
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // ── Set / upsert a setting ──────────────────────────
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting_' . $key);
        Cache::forget('all_settings');
    }

    // ── Get all settings as key→value array ────────────
    public static function allCached(): array
    {
        return Cache::rememberForever('all_settings', function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    // ── Clear all cached settings ───────────────────────
    public static function clearCache(): void
    {
        Cache::flush(); // or selectively clear each key
    }
}