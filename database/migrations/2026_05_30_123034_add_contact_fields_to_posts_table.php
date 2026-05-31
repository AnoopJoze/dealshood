<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('google_map_url');
            $table->string('phone_number')->nullable()->after('video_url');
            $table->string('whatsapp_number')->nullable()->after('phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'video_url',
                'phone_number',
                'whatsapp_number'
            ]);
        });
    }
};
