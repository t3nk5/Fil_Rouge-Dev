<?php

namespace App\Events;

use App\Enums\Matchmaking;
use App\Models\MatchmakingQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueLeaveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    private string $queue_id;

    /**
     * Create a new event instance.
     */
    public function __construct(MatchmakingQueue $queue)
    {
        $this->queue_id = $queue->id;
        $this->message = "queue $this->queue_id leaved by {$queue->user->name}";

        if ($opponent = $queue->gamePlayer->opponent) {
            $opponent->matchmakingQueue->status = Matchmaking::Waiting;
            $opponent->matchmakingQueue->save();
        }

        $queue->gamePlayer->delete();
        $queue->delete();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('queue-channel.leave-' . $this->queue_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue-leave';
    }
}
