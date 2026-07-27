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
        Schema::table('ad_submissions', function (Blueprint $table) {
            $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->string('custom_subcategory')->nullable()->after('subcategory_id');
            $table->string('custom_locality')->nullable()->after('locality_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subcategory_id');
            $table->dropColumn(['custom_subcategory', 'custom_locality']);
        });
    }
};
