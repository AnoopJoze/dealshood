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
       Schema::create('ad_submissions', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email');
        $table->string('phone')->nullable();
        $table->string('whatsapp')->nullable();
        $table->string('title');
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->foreignId('locality_id')->nullable()->constrained()->nullOnDelete();
        $table->text('description')->nullable();
        $table->string('company_name')->nullable();
        $table->string('location')->nullable();
        $table->decimal('offer_percentage', 5, 2)->nullable();
        $table->date('expiry_date')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('admin_notes')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_submissions');
    }
};
