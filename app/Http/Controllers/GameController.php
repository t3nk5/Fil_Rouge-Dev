<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\GameUpdatedEvent;

class GameController extends Controller
{
    private int $rows = 6;
    private int $columns = 7;

    public function restart()
    {
        session()->forget(['grid', 'turn', 'winner']);
        return redirect('/init');
    }
    private function checkWin(array $grid, string $player): bool
    {
        $rows = $this->rows;
        $cols = $this->columns;

        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c <= $cols - 4; $c++) {
                if ($grid[$r][$c] === $player &&
                    $grid[$r][$c+1] === $player &&
                    $grid[$r][$c+2] === $player &&
                    $grid[$r][$c+3] === $player) {
                    return true;
                }
            }
        }

        for ($c = 0; $c < $cols; $c++) {
            for ($r = 0; $r <= $rows - 4; $r++) {
                if ($grid[$r][$c] === $player &&
                    $grid[$r+1][$c] === $player &&
                    $grid[$r+2][$c] === $player &&
                    $grid[$r+3][$c] === $player) {
                    return true;
                }
            }
        }

        for ($r = 0; $r <= $rows - 4; $r++) {
            for ($c = 0; $c <= $cols - 4; $c++) {
                if ($grid[$r][$c] === $player &&
                    $grid[$r+1][$c+1] === $player &&
                    $grid[$r+2][$c+2] === $player &&
                    $grid[$r+3][$c+3] === $player) {
                    return true;
                }
            }
        }

        for ($r = 3; $r < $rows; $r++) {
            for ($c = 0; $c <= $cols - 4; $c++) {
                if ($grid[$r][$c] === $player &&
                    $grid[$r-1][$c+1] === $player &&
                    $grid[$r-2][$c+2] === $player &&
                    $grid[$r-3][$c+3] === $player) {
                    return true;
                }
            }
        }

        return false;
    }



    public function showGame()
    {
        $grid = session('grid') ?? array_fill(0, $this->rows, array_fill(0, $this->columns, null));
        $turn = session('turn') ?? 'R';

        return view('game', [
            'grid' => $grid,
            'turn' => $turn
        ]);
    }

    public function initGrid()
    {
        $grid = array_fill(0, $this->rows, array_fill(0, $this->columns, null));
        session(['grid' => $grid, 'turn' => 'R']);
        return redirect('/');
    }

    public function play(Request $request, int $column)
    {
        $grid = session('grid');
        $turn = session('turn');
        $winner = session('winner');

        if ($winner) return redirect('/');

        for ($row = $this->rows - 1; $row >= 0; $row--) {
            if ($grid[$row][$column] === null) {
                $grid[$row][$column] = $turn;

                if ($this->checkWin($grid, $turn)) {
                    session(['grid' => $grid, 'winner' => $turn]);
                    broadcast(new GameUpdatedEvent($grid, $turn, $turn))->toOthers();
                } else {
                    $nextTurn = $turn === 'R' ? 'J' : 'R';
                    session(['grid' => $grid, 'turn' => $nextTurn]);
                    broadcast(new GameUpdatedEvent($grid, $nextTurn, null))->toOthers();
                }

                break;
            }
        }

        return redirect('/');
    }


}
