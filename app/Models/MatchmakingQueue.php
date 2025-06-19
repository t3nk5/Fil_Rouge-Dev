<?php

namespace App\Models;

use App\Enums\Matchmaking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MatchmakingQueue extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'entry_time',
        'status',
    ];

    protected $casts = [
        'user_id' => 'string',
        'entry_time' => 'datetime',
        'status' => Matchmaking::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gamePlayer(): HasOne
    {
        return $this->hasOne(GamePlayer::class);
    }
}

