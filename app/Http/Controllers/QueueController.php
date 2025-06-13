<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(): View
    {
        return view('queue.index', [
            'user' => (object)[
                'username' => 'Joueur123',
                'gamesPlayed' => 15,
                'gamesWon' => 9,
                'winRate' => 60
            ],
            'opponent' => (object)[
                'username' => 'Adversaire87',
                'gamesPlayed' => 32,
                'gamesWon' => 22,
                'winRate' => 69
            ],
        ]);
    }
}
