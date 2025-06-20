<?php

namespace App\Http\Controllers;

use App\Events\GameStartCheckEvent;
use App\Events\QueueRequestEvent;
use App\Events\QueueJoinEvent;
use App\Events\QueueLeaveEvent;
use App\Models\MatchmakingQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function join(Request $request): JsonResponse
    {
        $user = $request->user();

        QueueJoinEvent::dispatch($user);
        GameStartCheckEvent::dispatch($user->queue->gamePlayer->game);

        return response()->json(['message' => "$user->name joining queue."]);
    }

    public function request(Request $request): JsonResponse
    {
        $user = $request->user();

        QueueRequestEvent::dispatch($user);

        return response()->json(['message' => "$user->name request for his queue object."]);
    }

    public function leave(Request $request): JsonResponse
    {
        $queueId = $request->input('queue_id');

        QueueLeaveEvent::dispatch(MatchmakingQueue::find($queueId));

        return response()->json(['message' => "{$request->user()->name} leaving queue."]);
    }
}
