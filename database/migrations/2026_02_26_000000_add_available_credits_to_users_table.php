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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'available_credits')) {
                $table->integer('available_credits')->default(0)->after('session_price');
            }
            if (! Schema::hasColumn('users', 'product_id')) {
                $table->string('product_id')->nullable()->after('available_credits');
            }
            if (! Schema::hasColumn('users', 'package')) {
                $table->string('package')->nullable()->after('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['available_credits', 'product_id', 'package']);
        });
    }
};
