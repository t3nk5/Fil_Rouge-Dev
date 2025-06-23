<?php

namespace App\Models;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

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
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'string',
        'password' => 'hashed',
    ];

    public function session(): HasOne
    {
        return $this->hasOne(Session::class, 'user_id');
    }

    public function queue(): HasOne
    {
        return $this->hasOne(MatchmakingQueue::class);
    }

    public function games(): HasManyThrough
    {
        return $this->hasManyThrough(
            Game::class,
            GamePlayer::class,
            'user_id',
            'id',
            'id',
            'game_id'
        );
    }

    public function stats(): array
    {
        $games = $this->games()
            ->where('status', '>', GameStatus::InProgress)
            ->with('players')
            ->get();

        return [
            'played' => $played = $games->count(),
            'wins' => $wins = $games->filter(fn($game) => $this->is($game->winner()?->user))->count(),
            'win_rate' => $played > 0 ? round(($wins / $played) * 100, 2) : 0,
        ];
    }
}
