<?php

namespace App\Http\Controllers;

use App\Events\GameStartEvent;
use App\Events\GameUpdateEvent;
use App\Events\PreGameUpdateEvent;
use App\Models\Game;
use App\Models\GameMove;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(string $id): View
    {
        return view('game.index', ['game_id' => $id]);
    }

    public function preUpdate(Request $request): JsonResponse
    {
        $game = Game::find($request->input('game_id'));
        $update = $request->input('update');

        PreGameUpdateEvent::dispatch($game, $update);

        if ($game->canStart())
            GameStartEvent::dispatch($game);

        return response()->json(['message' => "{$request->user()->name} request pre game ($game->id) infos update."]);
    }

    public function update(Request $request): JsonResponse
    {
        $game = Game::find($request->input('game_id'));

        if ($request->input('status'))
            $game->update(['status' => $request->input('status')]);

        if ($game->isEnded())
            foreach ($game->players as $player)
                $player->matchmakingQueue?->delete();

        GameUpdateEvent::dispatch($game);

        return response()->json(['message' => "{$request->user()->name} request game ($game->id) infos update."]);
    }

    public function place(Request $request): JsonResponse
    {
        $game = Game::find($request->input('game_id'));
        if ($request->user()->id !== $game->next()['player']->user->id)
            return response()->json([
                'message' => "{$request->user()->name} can't place a coin, it's not his turn (game: $game->id).",
                'success' => false,
            ]);

        GameMove::create([
            'game_id' => $game->id,
            'turn' => $game->next()['turn'],
            'player_index' => $game->next()['player']->player_index,
            'column' => $request->input('column'),
        ]);
        GameUpdateEvent::dispatch($game);

        return response()->json([
            'message' => "{$request->user()->name} place a coin in column {$request->input('column')} (game: $game->id).",
            'success' => true,
        ]);
    }


    public function template(): RedirectResponse
    {
        return redirect()->route('index');
    }
}
