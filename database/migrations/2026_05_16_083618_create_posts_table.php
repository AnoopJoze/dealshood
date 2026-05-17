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
        Schema::create('posts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Basic Details
            |--------------------------------------------------------------------------
            */
            $table->string('title');
            $table->string('slug')->unique();

            $table->longText('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('subcategory_id')
                ->nullable()
                ->constrained('subcategories')
                ->nullOnDelete();

            $table->foreignId('locality_id')
                ->nullable()
                ->constrained('localities')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Location Details
            |--------------------------------------------------------------------------
            */
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            // Address or place name
            $table->string('location')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Google Map Location
            |--------------------------------------------------------------------------
            */

            // Exact latitude & longitude
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Optional Google map url
            $table->text('google_map_url')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */
            $table->string('featured_image')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'draft',
                'published',
                'pending',
                'rejected',
                'expired'
            ])->default('draft');

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Publish & Expiry
            |--------------------------------------------------------------------------
            */
            $table->timestamp('published_at')->nullable();

            // Expiry date for ads/posts/listings
            $table->timestamp('expiry_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('views')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Soft Deletes & Timestamps
            |--------------------------------------------------------------------------
            */
            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('title');
            $table->index('status');
            $table->index('city');
            $table->index('published_at');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
