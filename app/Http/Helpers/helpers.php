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