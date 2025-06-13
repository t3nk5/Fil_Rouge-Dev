<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GameController extends Controller
{
    public function index(string $id): View
    {
        return view('game.index', ['game' => $id]);
    }
}
