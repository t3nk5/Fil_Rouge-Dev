<?php

namespace App\Models;

use App\Enums\GameStatus;
use App\Enums\Matchmaking;
use App\Enums\PlayerIndex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Game extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string)Str::uuid();
            }
        });
    }

    protected $fillable = [
        'status',
    ];

    protected $casts = [
        'id' => 'string',
        'status' => GameStatus::class,
    ];

    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function player(PlayerIndex $playerIndex): ?GamePlayer
    {
        return $this->players()
            ->where('player_index', $playerIndex)
            ->first();
    }

    public function moves(): HasMany
    {
        return $this->hasMany(GameMove::class);
    }

    public function hasPlace(): bool
    {
        return $this->status == GameStatus::InInit && $this->players->count() < 2;
    }

    public function canStart(): bool
    {
        return $this->status == GameStatus::InInit && !$this->hasPlace()
            && $this->players->every(fn($player) => $player->matchmakingQueue->status === Matchmaking::Ready);
    }

    public function getNewPlayerIndex(): ?PlayerIndex
    {
        if (!$this->player(PlayerIndex::Player1)) return PlayerIndex::Player1;
        if (!$this->player(PlayerIndex::Player2)) return PlayerIndex::Player2;
        return null;
    }

    public static function firstWithPlaceOrCreate(): Game
    {
        $game = self::all()->first(fn(Game $g) => $g->hasPlace());

        return $game ?? self::create();
    }

}
