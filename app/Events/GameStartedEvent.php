<?php
namespace App\Events;

use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GameStartedEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('game.' . $this->game->id);
    }

    public function broadcastWith()
    {
        return [
            'gameId' => $this->game->id,
            'player1' => $this->game->player1_id,
            'player2' => $this->game->player2_id,
        ];
    }
}
