<?php

namespace App\Events;

use App\Models\Game;
use App\Models\MatchmakingQueue;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $username;
    public readonly ?MatchmakingQueue $matchmakingQueue;

    public function __construct(private readonly User $user)
    {
        $this->matchmakingQueue = MatchmakingQueue::where('user_id', $this->user->id)->first();
        $this->username = $this->user->name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('queue-channel.request-' . $this->matchmakingQueue->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue-request';
    }
}
