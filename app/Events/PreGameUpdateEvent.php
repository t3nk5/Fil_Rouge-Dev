<?php

namespace App\Events;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\MatchmakingQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PreGameUpdateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var array<int, MatchmakingQueue>
     */
    public array $queues;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly Game $game)
    {
        $this->queues = $this->game->players
            ->mapWithKeys(fn($player) => [$player->matchmakingQueue->id => $player->matchmakingQueue->load('user')])
            ->all();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('game-channel.pre-update-' . $this->game->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'game-pre-update';
    }
}
