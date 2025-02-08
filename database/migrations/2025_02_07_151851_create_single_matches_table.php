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
        Schema::create('single_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_one');
            $table->foreign('player_one')->references('id')->on('players');
            $table->unsignedBigInteger('player_two');
            $table->foreign('player_two')->references('id')->on('players');
            $table->unsignedBigInteger('tournament_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->foreign('winner_id')->references('id')->on('players');

            $table->unsignedBigInteger('winner_set_one')->nullable();
            $table->foreign('winner_set_one')->references('id')->on('players');
            $table->string('set_one')->nullable();

            $table->unsignedBigInteger('winner_set_two')->nullable();
            $table->foreign('winner_set_two')->references('id')->on('players');
            $table->string('set_two')->nullable();

            $table->unsignedBigInteger('winner_set_three')->nullable();
            $table->foreign('winner_set_three')->references('id')->on('players');
            $table->string('set_three')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('single_matches');
    }
};
