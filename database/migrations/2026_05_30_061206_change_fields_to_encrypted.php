<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Converts sensitive columns to TEXT for AES-256-CBC encrypted values.
 *
 * MySQL won't change a column to TEXT/BLOB if an index exists on it.
 * This migration drops any indexes on affected columns first, then
 * widens the type to TEXT.
 *
 * Order:
 *   1. php artisan app:encrypt-existing-data   ← re-encrypt existing rows
 *   2. php artisan migrate                      ← run this migration
 */
return new class extends Migration
{
    /* ── affected columns per table ──────────────────── */
    private array $userCols = [
        'phone', 'whatsapp_number', 'address',
        'latitude', 'longitude',
        'location', 'company_name', 'about_me', 'website',
    ];

    private array $postCols = [
        'latitude', 'longitude', 'google_map_url',
        'country', 'state', 'city', 'location',
        'description', 'meta_title', 'meta_description', 'keywords',
    ];

    /* ─────────────────────────────────────────────────── */

    public function up(): void
    {
        // 1. Drop any non-primary indexes on affected columns so MySQL
        //    lets us convert them to TEXT.
        $this->dropColumnIndexes('users', $this->userCols);
        $this->dropColumnIndexes('posts', $this->postCols);

        // 2. Widen columns to TEXT.
        Schema::table('users', function (Blueprint $table) {
            $table->text('phone')->nullable()->change();
            $table->text('whatsapp_number')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->text('latitude')->nullable()->change();
            $table->text('longitude')->nullable()->change();
            $table->text('location')->nullable()->change();
            $table->text('company_name')->nullable()->change();
            $table->text('about_me')->nullable()->change();
            $table->text('website')->nullable()->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('latitude')->nullable()->change();
            $table->text('longitude')->nullable()->change();
            $table->text('google_map_url')->nullable()->change();
            $table->text('country')->nullable()->change();
            $table->text('state')->nullable()->change();
            $table->text('city')->nullable()->change();
            $table->text('location')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->text('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
            $table->text('keywords')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change();
            $table->string('whatsapp_number', 20)->nullable()->change();
            $table->string('address', 500)->nullable()->change();
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
            $table->string('location', 255)->nullable()->change();
            $table->string('company_name', 255)->nullable()->change();
            $table->text('about_me')->nullable()->change();
            $table->string('website', 255)->nullable()->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
            $table->string('google_map_url', 500)->nullable()->change();
            $table->string('country', 100)->nullable()->change();
            $table->string('state', 100)->nullable()->change();
            $table->string('city', 100)->nullable()->change();
            $table->string('location', 255)->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('meta_title', 255)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
            $table->string('keywords', 500)->nullable()->change();
        });
    }

    /* ─────────────────────────────────────────────────────────────
     | Drop every non-primary index that covers any of the given
     | columns.  Uses SHOW INDEX so it works on any MySQL version.
     ───────────────────────────────────────────────────────────── */
    private function dropColumnIndexes(string $table, array $columns): void
    {
        // Fetch all indexes for this table that touch one of our columns
        $placeholders = implode(',', array_fill(0, count($columns), '?'));

        $rows = DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Column_name IN ({$placeholders})",
            $columns
        );

        // Collect unique non-primary index names
        $toDrop = collect($rows)
            ->where('Key_name', '!=', 'PRIMARY')
            ->pluck('Key_name')
            ->unique();

        foreach ($toDrop as $indexName) {
            // Wrap in try/catch so a race-drop never kills the migration
            try {
                DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
            } catch (\Throwable $e) {
                // Index already gone — safe to continue
            }
        }
    }
};