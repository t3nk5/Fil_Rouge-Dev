<?php

namespace App\Http\Middleware;

use App\Enums\Matchmaking;
use App\Models\Game;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InGame
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $game = Game::find($request->input('game_id'));

        if (!$game->players->contains(fn($player) => $player->user->is($user)))
            return response()->json([
                'message' => 'You are not a participant in this game.'
            ], Response::HTTP_UNAUTHORIZED);

        return $next($request);
    }
}
/*
 redirect()->route('index')
                ->with('error', "$user->name must be in queue to access this page.")
*/
