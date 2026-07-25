<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('shared_to_facebook')->default(false)->after('disclaimer');
            $table->boolean('shared_to_instagram')->default(false)->after('shared_to_facebook');
            $table->string('facebook_post_id')->nullable()->after('shared_to_instagram');
            $table->string('instagram_post_id')->nullable()->after('facebook_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['shared_to_facebook', 'shared_to_instagram', 'facebook_post_id', 'instagram_post_id']);
        });
    }
};
