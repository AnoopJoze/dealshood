<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('session_id')->nullable();
            $table->unsignedTinyInteger('rating'); // 1–5
            $table->timestamps();

            $table->index(['post_id', 'ip_address']);
            $table->index(['post_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_ratings');
    }
};