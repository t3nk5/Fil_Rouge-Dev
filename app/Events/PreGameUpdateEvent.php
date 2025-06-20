<?php

namespace App\Events;

use App\Enums\Matchmaking;
use App\Models\Game;
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
    public string $game_id;

    /**
     * Create a new event instance.
     */
    public function __construct(private readonly Game $game, ?array $update = null)
    {
        $this->game_id = $this->game->id;
        $this->queues = $this->game->players
            ->mapWithKeys(fn($player) => [$player->matchmakingQueue->id => $player->matchmakingQueue->load('user')])
            ->all();

        if ($update) {
            switch ($update['status']) {
                case 'ready':
                    $this->queues[$update['queue_id']]->update([
                        'status' => Matchmaking::Ready,
                        'ready_at' => now(),
                    ]);
                    break;
                case 'not-ready':
                    $this->queues[$update['queue_id']]->update([
                        'status' => Matchmaking::NotReady,
                        'ready_at' => null,
                    ]);
                    break;
            }
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
            new PrivateChannel('game-channel.pre-update-' . $this->game->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'game-pre-update';
    }
}
