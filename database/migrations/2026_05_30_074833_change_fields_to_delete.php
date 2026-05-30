<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds deleted_at to every table that needs soft delete.
 * Skips the column if it already exists (safe to re-run).
 */
return new class extends Migration
{
    /** Tables + their primary reason for soft delete */
    private array $tables = [
        'categories',
        'subcategories',
        'localities',
        'posts',   // already has it if migrated before — skipped automatically
        'users',   // same
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->softDeletes(); // adds deleted_at TIMESTAMP NULL DEFAULT NULL
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropSoftDeletes();
                });
            }
        }
    }
};
