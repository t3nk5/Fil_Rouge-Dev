<?php

namespace App\Events;

use App\Enums\GameStatus;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueStatsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly array $data;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->data = [
            'current_games' => Game::where('status', GameStatus::InProgress)->count(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('stats-channel.queue'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'stats-queue';
    }
}
