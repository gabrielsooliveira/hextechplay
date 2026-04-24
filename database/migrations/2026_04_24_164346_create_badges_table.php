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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->default('fas fa-medal');
            $table->string('tier')->default('iron'); // iron, bronze, silver, gold, platinum, emerald, diamond, master, grandmaster, challenger
            $table->string('game')->nullable(); // WordLoL, LoreQuestion, ClickChallenger
            $table->string('requirement_type'); // score, wins, etc
            $table->integer('requirement_value'); // e.g., 1000, 50, 1
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
