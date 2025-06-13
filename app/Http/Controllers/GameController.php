<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GameController extends Controller
{
    public function index(string $id): View
    {
        return view('game.index', [
            'game' => $id,
            'user' => (object)[
                'username' => 'Joueur123',
                'gamesPlayed' => 15,
                'gamesWon' => 9,
                'winRate' => 60
            ]]);
    }
}
