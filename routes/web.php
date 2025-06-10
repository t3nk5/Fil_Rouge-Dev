<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;


Route::get('/', [GameController::class, 'showGame']);

Route::get('/init', [GameController::class, 'initGrid']);

Route::get('/play/{column}', [GameController::class, 'play']);

Route::get('/restart', [GameController::class, 'restart'])->name('restart');


