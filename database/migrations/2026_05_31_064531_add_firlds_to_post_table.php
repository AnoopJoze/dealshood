<?php
// database/migrations/xxxx_add_contact_fields_to_posts.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Only add if they don't already exist
            if (! Schema::hasColumn('posts', 'company_name')) {
                $table->text('company_name')->nullable()->after('whatsapp_number');
            }
            // phone_number, whatsapp_number, google_map_url already exist per model
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('company_name');
        });
    }
};