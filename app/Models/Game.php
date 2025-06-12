<?php

namespace App\Models;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $casts = [
        'status' => GameStatus::class,
    ];
}
