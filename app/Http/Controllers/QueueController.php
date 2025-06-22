<?php

namespace App\Http\Controllers;

use App\Events\PreGameUpdateEvent;
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
        return view('queue.index');
    }

    public function join(Request $request): JsonResponse
    {
        $user = $request->user();

        QueueJoinEvent::dispatch($user);
        PreGameUpdateEvent::dispatch($user->queue->gamePlayer->game);

        return response()->json(['message' => "$user->name joining queue."]);
    }

    public function leave(Request $request): JsonResponse
    {
        $queueId = $request->input('queue_id');
        $game = MatchmakingQueue::find($queueId)->gamePlayer->game;

        QueueLeaveEvent::dispatch(MatchmakingQueue::find($queueId));
        PreGameUpdateEvent::dispatch($game);

        return response()->json(['message' => "{$request->user()->name} leaving queue."]);
    }
}
