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
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('leader_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->after('leader_id')->constrained('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropForeign(['event_id']);
            $table->dropColumn(['leader_id', 'event_id']);
        });
    }
};
