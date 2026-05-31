<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_add_session_id_to_engagement_tables.php

public function up(): void
{
    Schema::table('post_views', function (Blueprint $table) {
        $table->string('session_id', 100)->nullable()->after('ip_address');
        $table->index(['post_id', 'session_id']);
    });

    Schema::table('post_shares', function (Blueprint $table) {
        $table->string('ip_address', 100)->nullable()->after('platform');
        $table->string('session_id', 100)->nullable()->after('ip_address');
        $table->index(['post_id', 'session_id']);
    });
}

public function down(): void
{
    Schema::table('post_views', function (Blueprint $table) {
        $table->dropIndex(['post_id', 'session_id']);
        $table->dropColumn('session_id');
    });

    Schema::table('post_shares', function (Blueprint $table) {
        $table->dropIndex(['post_id', 'session_id']);
        $table->dropColumn('ip_address');
        $table->dropColumn('session_id');
    });
}
};
