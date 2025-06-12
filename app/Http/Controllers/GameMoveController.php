<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMove;
use Illuminate\Http\Request;
use App\Events\GameUpdatedEvent;
use App\Models\User;

class GameMoveController extends Controller
{
    private int $rows = 6;
    private int $columns = 7;

    public function store(Request $request, Game $game, int $column)
    {
        $player = $request->user();

        if (!in_array($player->id, [$game->player1_id, $game->player2_id])) {
            return response()->json(['error' => 'Vous ne participez pas à cette partie.'], 403);
        }

        $grid = array_fill(0, $this->rows, array_fill(0, $this->columns, null));
        $turnNumber = 1;
        foreach ($game->moves as $move) {
            for ($row = $this->rows - 1; $row >= 0; $row--) {
                if ($grid[$row][$move->column] === null) {
                    $grid[$row][$move->column] = $move->player_id;
                    break;
                }
            }
            $turnNumber = max($turnNumber, $move->turn + 1);
        }

        $expectedPlayerId = $turnNumber % 2 === 1 ? $game->player1_id : $game->player2_id;

        if ($player->id !== $expectedPlayerId) {
            return response()->json(['error' => 'Ce n’est pas votre tour.'], 403);
        }

        if ($grid[0][$column] !== null) {
            return response()->json(['error' => 'Cette colonne est pleine.'], 422);
        }


        for ($row = $this->rows - 1; $row >= 0; $row--) {
            if ($grid[$row][$column] === null) {
                $grid[$row][$column] = $player->id;
                break;
            }
        }


        $move = new GameMove([
            'id' => Str::uuid()->toString(),
            'game_id' => $game->id,
            'turn' => $turnNumber,
            'player_id' => $player->id,
            'column' => $column,
        ]);
        $move->save();

        $winner = $this->checkWin($grid, $player->id);
        if ($winner) {
            $game->status = 'finished';
            $game->save();
        }

        broadcast(new GameUpdatedEvent(
            $game->id,
            $grid,
            $winner ? null : ($expectedPlayerId === $game->player1_id ? $game->player2_id : $game->player1_id),
            $winner ? $player->id : null
        ))->toOthers();

        return response()->json([
            'grid' => $grid,
            'turn' => $winner ? null : ($expectedPlayerId === $game->player1_id ? $game->player2_id : $game->player1_id),
            'winner' => $winner ? $player->id : null,
        ]);
    }

    private function checkWin(array $grid, string $playerId): bool
    {
        $rows = $this->rows;
        $cols = $this->columns;

        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c <= $cols - 4; $c++) {
                if (
                    $grid[$r][$c] === $playerId &&
                    $grid[$r][$c + 1] === $playerId &&
                    $grid[$r][$c + 2] === $playerId &&
                    $grid[$r][$c + 3] === $playerId
                ) return true;
            }
        }

        for ($c = 0; $c < $cols; $c++) {
            for ($r = 0; $r <= $rows - 4; $r++) {
                if (
                    $grid[$r][$c] === $playerId &&
                    $grid[$r + 1][$c] === $playerId &&
                    $grid[$r + 2][$c] === $playerId &&
                    $grid[$r + 3][$c] === $playerId
                ) return true;
            }
        }

        for ($r = 0; $r <= $rows - 4; $r++) {
            for ($c = 0; $c <= $cols - 4; $c++) {
                if (
                    $grid[$r][$c] === $playerId &&
                    $grid[$r + 1][$c + 1] === $playerId &&
                    $grid[$r + 2][$c + 2] === $playerId &&
                    $grid[$r + 3][$c + 3] === $playerId
                ) return true;
            }
        }

        for ($r = 3; $r < $rows; $r++) {
            for ($c = 0; $c <= $cols - 4; $c++) {
                if (
                    $grid[$r][$c] === $playerId &&
                    $grid[$r - 1][$c + 1] === $playerId &&
                    $grid[$r - 2][$c + 2] === $playerId &&
                    $grid[$r - 3][$c + 3] === $playerId
                ) return true;
            }
        }

        return false;
    }
}
