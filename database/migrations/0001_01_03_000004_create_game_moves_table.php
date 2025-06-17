<?php

use App\Models\Game;
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
        Schema::create('game_moves', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Game::class)->constrained()->onDelete('cascade');
            $table->integer('turn');
            $table->tinyInteger('player_index');
            $table->tinyInteger('column');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_moves');
    }
};
