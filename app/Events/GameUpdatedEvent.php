<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class GameUpdatedEvent implements ShouldBroadcast
    {
    use InteractsWithSockets, SerializesModels;

    public $grid;
    public $turn;
    public $winner;

    public function __construct($grid, $turn, $winner)
        {
        $this->grid = $grid;
        $this->turn = $turn;
        $this->winner = $winner;
    }

    public function broadcastOn()
    {
        return new Channel('game-channel');
    }

    public function broadcastAs()
    {
    return 'game-updated';
    }
    
    }
