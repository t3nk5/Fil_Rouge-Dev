<?php

namespace App\Http\Controllers;

use App\Events\GameStartCheckEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(string $id): View
    {
        return view('game.index', ['game' => $id]);
    }

    public function startCheck(Request $request): JsonResponse
    {
        $user = $request->user();

        GameStartCheckEvent::dispatch($user->queue);

        return response()->json(['message' => "$user->name request game ({$user->queue->gamePlayer->game->id}) start check."]);
    }
}
