<?php

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Game::class)->constrained()->onDelete('cascade');
            $table->tinyInteger('player_index');
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
