<?php

namespace App\Events;

use App\Enums\GameStatus;
use App\Enums\Matchmaking;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameStartEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly Game $game)
    {
        $this->game->update(['status' => GameStatus::InProgress]);

        foreach ($this->game->players as $player)
            $player->matchmakingQueue->update(['status' => Matchmaking::InGame]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('game-channel.start-' . $this->game->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'game-start';
    }
}
