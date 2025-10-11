<?php

use App\Models\User;
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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'others']);
            $table->text('about_us')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('affiliated_offer')->nullable();
            $table->json('topic_offer')->nullable();
            $table->string('categories')->nullable();
            $table->string('sub_categories')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
