<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(): View
    {
        return view('queue.index', [
            'opponent' => (object)[
                'username' => 'Adversaire87',
                'gamesPlayed' => 32,
                'gamesWon' => 22,
                'winRate' => 69
            ],
        ]);
    }
}
