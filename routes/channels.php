<?php

use App\Models\GamePlayer;
use App\Models\MatchmakingQueue;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('queue-channel.join-{userId}', function (User $user, string $userId) {
    return $user->is(User::find($userId));
});

Broadcast::channel('queue-channel.request-{queueId}', function (User $user, int $queueId) {
    $queue = MatchmakingQueue::find($queueId);
    return $user->is($queue?->user);
});

Broadcast::channel('queue-channel.leave-{queueId}', function (User $user, int $queueId) {
    $queue = MatchmakingQueue::find($queueId);
    return $user->is($queue?->user);
});

Broadcast::channel('queue-channel.leave-{queueId}', function (User $user, int $queueId) {
    $queue = MatchmakingQueue::find($queueId);
    return $user->is($queue?->user);
});

Broadcast::channel('game-channel.start-check-{gameId}', function (User $user, string $gameId) {
    return GamePlayer::where('game_id', $gameId)
        ->where('user_id', $user->id)
        ->exists();
});
