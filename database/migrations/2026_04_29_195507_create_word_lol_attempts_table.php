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
        Schema::create('word_lol_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('guest_id')->nullable()->index();
            $table->foreignId('daily_word_id')->constrained('daily_words')->onDelete('cascade');
            $table->json('state');
            $table->boolean('finished')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'daily_word_id']);
            $table->index(['guest_id', 'daily_word_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('word_lol_attempts');
    }
};
