<?php

namespace App\Http\Controllers;

use App\Events\PreGameUpdateEvent;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(string $id): View
    {
        return view('game.index', ['game' => $id]);
    }

    public function preUpdate(Request $request): JsonResponse
    {
        $game = Game::find($request->input('game_id'));
        $update = $request->input('update');

        PreGameUpdateEvent::dispatch($game, $update);

        return response()->json(['message' => "{$request->user()->name} request game ($game->id) start check."]);
    }
}
