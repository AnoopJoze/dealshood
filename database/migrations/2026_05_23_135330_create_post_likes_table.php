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
        Schema::create('post_likes', function (Blueprint $table) {

    $table->id();

    $table->foreignId('post_id')
        ->constrained()
        ->cascadeOnDelete();

    // nullable because guest users
    $table->foreignId('user_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->ipAddress('ip_address')->nullable();

    // optional browser/session token
    $table->string('session_id')->nullable();

    $table->timestamps();

    // prevent duplicate guest likes
    $table->unique([
        'post_id',
        'ip_address'
    ]);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_likes');
    }
};
