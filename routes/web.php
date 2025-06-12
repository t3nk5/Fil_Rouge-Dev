<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Events\TestPusherEvent;


Route::get('/home', [GameController::class, 'showGame']);

Route::get('/init', [GameController::class, 'initGrid']);

Route::get('/play/{column}', [GameController::class, 'play']);

Route::get('/restart', [GameController::class, 'restart'])->name('restart');




Route::get('/', [GameController::class, 'home'])->name('index');

Route::get('/login', [GameController::class, 'login'])->name('login');

Route::get('/waiting', [GameController::class, 'waiting'])->name('waiting');

Route::get('/game', [GameController::class, 'game'])->name('game');
