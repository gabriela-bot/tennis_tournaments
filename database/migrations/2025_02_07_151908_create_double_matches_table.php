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
        Schema::create('double_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_one');
            $table->foreign('group_one')->references('id')->on('groups');
            $table->unsignedBigInteger('group_two');
            $table->foreign('group_two')->references('id')->on('groups');
            $table->unsignedBigInteger('tournament_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->unsignedBigInteger('group_winner_id')->nullable();
            $table->foreign('group_winner_id')->references('id')->on('groups');

            $table->unsignedBigInteger('winner_set_one')->nullable();
            $table->foreign('winner_set_one')->references('id')->on('groups');
            $table->string('set_one')->nullable();

            $table->unsignedBigInteger('winner_set_two')->nullable();
            $table->foreign('winner_set_two')->references('id')->on('groups');
            $table->string('set_two')->nullable();

            $table->unsignedBigInteger('winner_set_three')->nullable();
            $table->foreign('winner_set_three')->references('id')->on('groups');
            $table->string('set_three')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('double_matches');
    }
};
