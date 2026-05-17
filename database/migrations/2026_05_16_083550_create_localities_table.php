<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localities', function (Blueprint $table) {

            $table->id();

            // Hierarchy support
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('localities')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            // Type: country, state, city, area
            $table->enum('type', ['country', 'state', 'city', 'area']);

            // Optional metadata
            $table->string('code')->nullable(); // UAE, IN, etc.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['type', 'name']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
