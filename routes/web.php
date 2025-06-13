<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController2;

Route::get('/', function () {
    return view('index', ['user' => (object)[
        'username' => 'Joueur123',
        'gamesPlayed' => 15,
        'gamesWon' => 9,
        'winRate' => 60]
    ]);
})->name('index');

Route::get('/queue', [QueueController::class, 'index'])->name('queue');

Route::prefix('/game')->name('game.')->controller(GameController::class)->group(function () {
    Route::get('/{id}', 'index')->name('index');
});

Route::prefix('/test')->name('test')->group(function () {
    Route::get('/home', [GameController2::class, 'showGame']);

    Route::get('/init', [GameController2::class, 'initGrid']);

    Route::get('/play/{column}', [GameController2::class, 'play']);

    Route::get('/restart', [GameController2::class, 'restart'])->name('restart');


    Route::get('/', [GameController2::class, 'home'])->name('index');

    Route::get('/login', [GameController2::class, 'login'])->name('login');

    Route::get('/waiting', [GameController2::class, 'waiting'])->name('waiting');

    Route::get('/game', [GameController2::class, 'game'])->name('game');
});

