<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameMove extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'game_id',
        'turn',
        'player_id',
        'column',
    ];

    protected $casts = [
        'turn' => 'integer',
        'column' => 'integer',
        'game_id' => 'string',
        'player_id' => 'string',
    ];


    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id');
    }
}
