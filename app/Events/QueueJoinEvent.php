<?php

namespace App\Events;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\MatchmakingQueue;
use App\Models\Session;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueJoinEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public int $queue_id;

    private readonly Game $game;
    private readonly GamePlayer $player;

    public function __construct(private readonly User $user)
    {
        $queue = MatchmakingQueue::firstOrCreate(['user_id' => $this->user->id,]);
        $this->queue_id = $queue->id;

        $this->game = Game::firstWithPlaceOrCreate();
        $this->player = GamePlayer::firstOrCreate(
            ['matchmaking_queue_id' => $queue->id],
            [
                'game_id' => $this->game->id,
                'player_index' => $this->game->getNewPlayerIndex(),
                'user_id' => $this->user->id,
            ]
        );

        $this->message = "{$this->user->name} joined queue ($this->queue_id) ; game {$this->game->id}, player {$this->player->player_index->value}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('queue-channel.join'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue-join';
    }
}
