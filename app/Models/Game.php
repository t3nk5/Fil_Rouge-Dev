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
        return $this->hasMany(GamePlayer::class)->orderBy('player_index');
    }

    public function player(PlayerIndex $playerIndex): ?GamePlayer
    {
        return $this->players()
            ->where('player_index', $playerIndex)
            ->first();
    }

    public function moves(): HasMany
    {
        return $this->hasMany(GameMove::class)
            ->orderBy('turn')
            ->orderBy('player_index');
    }

    public function lastMove(): ?GameMove
    {
        return $this->hasMany(GameMove::class)
            ->orderByDesc('turn')
            ->orderByDesc('player_index')
            ->first();
    }

    public function next(): array
    {
        $lastMove = $this->lastMove();

        $nextTurn = $lastMove
            ? $lastMove->turn + ($lastMove->player_index === PlayerIndex::Player2 ? 1 : 0)
            : 1;

        $nextPlayerIndex = $lastMove
            ? ($lastMove->player_index === PlayerIndex::Player1 ? PlayerIndex::Player2 : PlayerIndex::Player1)
            : PlayerIndex::Player1;

        return [
            'turn' => $nextTurn,
            'player' => $this->player($nextPlayerIndex),
        ];
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

    public function hasStarted(): bool
    {
        return $this->status == GameStatus::InProgress;
    }

    public function isEnded(): bool
    {
        return $this->status === GameStatus::Draw || $this->status === GameStatus::Player1_Win || $this->status === GameStatus::Player2_Win;
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
