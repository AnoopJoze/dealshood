<?php
if (!function_exists('setting')) {
    /**
     * Get a site setting value.
     * Usage: setting('site_name')  or  setting('site_name', 'Default')
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('site_logo_url')) {
    /**
     * Admin-uploaded site logo, falling back to the bundled default asset.
     */
    function site_logo_url(): string
    {
        $path = setting('site_logo');
        return $path
            ? url(\Illuminate\Support\Facades\Storage::url($path))
            : asset('frontend/img/dealshood.png');
    }
}

if (!function_exists('site_favicon_url')) {
    /**
     * Admin-uploaded favicon, falling back to the bundled default asset.
     */
    function site_favicon_url(): string
    {
        $path = setting('site_favicon');
        return $path
            ? url(\Illuminate\Support\Facades\Storage::url($path))
            : asset('frontend/img/favicon.ico');
    }
}

if (!function_exists('site_og_image_url')) {
    /**
     * Admin-uploaded default OG/share image, falling back to the site logo.
     * Always HTTPS — WhatsApp/Facebook crawlers refuse plain-HTTP images.
     */
    function site_og_image_url(): string
    {
        $path = setting('og_image');
        $url  = $path
            ? url(\Illuminate\Support\Facades\Storage::url($path))
            : site_logo_url();

        return str_replace('http://', 'https://', $url);
    }
}