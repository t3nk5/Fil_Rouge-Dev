<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class GameUpdatedEvent implements ShouldBroadcast
    {

    public string $gameId;
    public array $grid;
    public ?string $currentPlayerId;
    public ?string $winnerId;

    public function __construct(string $gameId, array $grid, ?string $currentPlayerId, ?string $winnerId)
    {
        $this->gameId = $gameId;
        $this->grid = $grid;
        $this->currentPlayerId = $currentPlayerId;
        $this->winnerId = $winnerId;
    }

    public function broadcastOn(): array
    {
        return ['game-channel'];
    }

    public function broadcastAs(): string
    {
        return 'GameUpdatedEvent';
    }


    }


