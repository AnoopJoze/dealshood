<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::allCached();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:100',
            'site_tagline'     => 'nullable|string|max:200',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords'    => 'nullable|string|max:500',
            'contact_email'    => 'nullable|email|max:100',
            'contact_phone'    => 'nullable|string|max:20',
            'whatsapp_number'  => 'nullable|string|max:20',
            'instagram_url'    => 'nullable|url|max:200',
            'facebook_url'     => 'nullable|url|max:200',
            'twitter_url'      => 'nullable|url|max:200',
            'youtube_url'      => 'nullable|url|max:200',
            'address'          => 'nullable|string|max:300',
            'google_analytics_id' => 'nullable|string|max:50',
            'footer_text'      => 'nullable|string|max:300',
            'posts_per_page'   => 'nullable|integer|min:1|max:100',
            'site_logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_favicon'     => 'nullable|image|mimes:png,ico,svg|max:512',
            'og_image'         => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
        ]);

        // ── Text fields ──────────────────────────────────
        $textFields = [
            'site_name','site_tagline','meta_title','meta_description',
            'meta_keywords','contact_email','contact_phone','whatsapp_number',
            'instagram_url','facebook_url','twitter_url','youtube_url',
            'address','google_analytics_id','footer_text','posts_per_page',
            'maintenance_mode','admin_email_notify',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field) ?? '');
            }
        }

        // Checkboxes (unchecked = not in request)
        Setting::set('maintenance_mode',   $request->has('maintenance_mode')   ? '1' : '0');
        Setting::set('admin_email_notify', $request->has('admin_email_notify') ? '1' : '0');

        // ── File uploads ─────────────────────────────────
        foreach (['site_logo', 'site_favicon', 'og_image'] as $fileField) {
            if ($request->hasFile($fileField)) {
                // Delete old file
                $old = Setting::get($fileField);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($fileField)->store('settings', 'public');
                Setting::set($fileField, $path);
            }
        }

        // Clear all cached settings
        Cache::forget('all_settings');

        return back()->with('success', 'Settings saved successfully.');
    }

    // Remove a specific file (logo/favicon/og_image)
    public function removeFile(Request $request, string $key)
    {
        abort_unless(in_array($key, ['site_logo','site_favicon','og_image']), 400);

        $path = Setting::get($key);
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        Setting::set($key, null);

        return back()->with('success', ucfirst(str_replace('_', ' ', $key)) . ' removed.');
    }
}