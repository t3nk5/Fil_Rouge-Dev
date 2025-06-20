<?php

namespace App\Models;

use App\Enums\PlayerIndex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GameMove extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'game_id',
        'turn',
        'player_index',
        'column',
    ];

    protected $casts = [
        'game_id' => 'string',
        'turn' => 'integer',
        'player_index' => PlayerIndex::class,
        'column' => 'integer',
    ];


    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function player(): ?GamePlayer
    {
        return $this->game()->first()->player($this->player_index);
    }
}
