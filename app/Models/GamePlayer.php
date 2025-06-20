<?php

namespace App\Models;

use App\Enums\PlayerIndex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class GamePlayer extends Model
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
        'game_id',
        'player_index',
        'user_id',
        'matchmaking_queue_id',
    ];

    protected $casts = [
        'game_id' => 'string',
        'player_index' => PlayerIndex::class,
        'user_id' => 'string',
        'matchmaking_queue_id' => 'string',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matchmakingQueue(): BelongsTo
    {
        return $this->belongsTo(MatchmakingQueue::class);
    }

    public function opponent(): HasOne
    {
        return $this->hasOne(GamePlayer::class, 'game_id', 'game_id')
            ->where('player_index', '!=', $this->player_index);
    }
}
