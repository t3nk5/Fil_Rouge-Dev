<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class GameUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly array $next;

    public function __construct(public readonly Game $game)
    {
        $this->game->load(['players.user', 'moves']);
        $this->next = $this->game->next();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('game-channel.update-' . $this->game->id);
    }

    public function broadcastAs()
    {
        return 'game-update';
    }

}
