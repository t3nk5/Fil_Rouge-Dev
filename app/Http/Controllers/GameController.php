<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMove;
use App\Models\Queue;
use Illuminate\Http\Request;
use App\Events\GameUpdatedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


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

    public function home()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }

    public function waiting()
    {
        $userId = Auth::id();

        $alreadyInQueue = Queue::where('session_id', $userId)->exists();

        if (!$alreadyInQueue) {
            Queue::create([
                'session_id' => $userId,
                'entry_time' => now(),
            ]);
        }


        $waitingPlayers = Queue::orderBy('entry_time')->take(2)->get();

        if ($waitingPlayers->count() >= 2) {
            $player1 = $waitingPlayers[0];
            $player2 = $waitingPlayers[1];


            $game = Game::create([
                'player1_id' => $player1->session_id,
                'player2_id' => $player2->session_id,
                'status' => 'active',
            ]);


            Queue::whereIn('session_id', [$player1->session_id, $player2->session_id])->delete();


            broadcast(new \App\Events\GameStartedEvent($game))->toOthers();

            if ($userId == $player1->session_id || $userId == $player2->session_id) {
                return redirect('/game/' . $game->id);
            }
        }


        $allWaitingPlayers = Queue::orderBy('entry_time')->get();

        return view('waiting', ['waitingPlayers' => $allWaitingPlayers]);
    }

    public function game()
    {
        return view('game');
    }

    public function showGame()
    {
        $grid = session('grid') ?? array_fill(0, $this->rows, array_fill(0, $this->columns, null));
        $turn = session('turn') ?? 'R';

        $users = User::all();


        $game = Game::first();

        return view('test', [
            'grid' => $grid,
            'turn' => $turn,
            'users' => $users,
            'game' => $game, // <- ajoute ça pour que la vue ait la variable $game
        ]);
    }

    public function initGrid()
    {
        $grid = array_fill(0, $this->rows, array_fill(0, $this->columns, null));
        session(['grid' => $grid, 'turn' => 'R']);
        return redirect('/');
    }

    public function play(Request $request, string $gameId, int $column)
    {
        $user = Auth::user();
        $game = Game::with('moves')->findOrFail($gameId);

        if (!in_array($user->id, [$game->player1_id, $game->player2_id])) {
            abort(403, 'Vous ne participez pas à cette partie.');
        }

        $lastMove = $game->moves->sortByDesc('turn')->first();
        $expectedPlayer = !$lastMove
            ? $game->player1_id
            : ($lastMove->player_id === $game->player1_id ? $game->player2_id : $game->player1_id);

        if ($user->id !== $expectedPlayer) {
            return response()->json(['error' => "Ce n'est pas votre tour."], 403);
        }


        $grid = array_fill(0, 6, array_fill(0, 7, null));
        foreach ($game->moves as $move) {
            for ($row = 5; $row >= 0; $row--) {
                if ($grid[$row][$move->column] === null) {
                    $grid[$row][$move->column] = $move->player_id;
                    break;
                }
            }
        }


        $topCell = $grid[0][$column];
        if ($topCell !== null) {
            return response()->json(['error' => "Colonne pleine"], 400);
        }

        for ($row = 5; $row >= 0; $row--) {
            if ($grid[$row][$column] === null) {
                $grid[$row][$column] = $user->id;
                break;
            }
        }

        $move = GameMove::create([
            'id' => Str::uuid(),
            'game_id' => $game->id,
            'player_id' => $user->id,
            'turn' => $lastMove ? $lastMove->turn + 1 : 1,
            'column' => $column,
        ]);

        $winnerId = $this->checkWin($grid, $user->id) ? $user->id : null;

        if ($winnerId) {
            $game->status = 'finished';
            $game->save();
        }

        broadcast(new GameUpdatedEvent($grid, $user->id, $winnerId))->toOthers();

        return response()->json([
            'grid' => $grid,
            'winner' => $winnerId,
            'nextTurn' => $winnerId ? null : ($expectedPlayer === $game->player1_id ? $game->player2_id : $game->player1_id)
        ]);
    }


}
