<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $keyType = 'string';
    public $incrementing = false;

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
        return $this->hasOne(Session::class);
    }

    public function gamesAsPlayer1(): HasMany
    {
        return $this->hasMany(Game::class, 'player1_id');
    }

    public function gamesAsPlayer2(): HasMany
    {
        return $this->hasMany(Game::class, 'player2_id');
    }
}
