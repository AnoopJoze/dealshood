<?php
// database/migrations/xxxx_xx_xx_create_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            'site_name'            => 'DealsHood',
            'site_tagline'         => 'Discover the best deals near you.',
            'site_logo'            => null,
            'site_favicon'         => null,
            'meta_title'           => 'DealsHood — Discover the Best Deals Near You',
            'meta_description'     => 'Find great offers from your neighbourhood, every day.',
            'meta_keywords'        => 'deals, offers, discounts, local deals',
            'og_image'             => null,
            'contact_email'        => 'admin@dealshood.com',
            'contact_phone'        => '',
            'whatsapp_number'      => '918086087050',
            'instagram_url'        => 'https://www.instagram.com/dealshood',
            'facebook_url'         => '',
            'twitter_url'          => '',
            'youtube_url'          => '',
            'address'              => '',
            'google_analytics_id'  => '',
            'footer_text'          => '© 2025 DealsHood. All rights reserved.',
            'maintenance_mode'     => '0',
            'posts_per_page'       => '12',
            'admin_email_notify'   => '1',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->insert([
                'key'        => $key,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};