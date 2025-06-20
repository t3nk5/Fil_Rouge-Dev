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

class GameStartCheckEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly Game $game;
    private readonly GamePlayer $player;
    private readonly ?MatchmakingQueue $opponentQueue;

    /**
     * @var array<int, MatchmakingQueue>
     */
    public array $queues;

    /**
     * Create a new event instance.
     */
    public function __construct(private readonly MatchmakingQueue $selfQueue)
    {
        $this->player = $this->selfQueue->gamePlayer;
        $this->game = $this->player->game;
        $this->opponentQueue = $this->player->opponent?->matchmakingQueue;

        $this->queues = [
            $this->selfQueue->id => $this->selfQueue->load('user'),
        ];

        if ($this->opponentQueue !== null) {
            $this->queues[$this->opponentQueue->id] = $this->opponentQueue->load('user');
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('game-channel.start-check-' . $this->game->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'game-start-check';
    }
}
