<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController2;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::prefix('/queue')->name('queue.')->controller(QueueController::class)->group(function () {
    Route::get('/', 'index')->name('index')->middleware(['auth', 'in-queue']);
    Route::post('/join', 'join')->name('join')->middleware('auth');
    Route::post('/request', 'request')->name('request')->middleware('auth');
    Route::post('/leave', 'leave')->name('leave')->middleware(['auth', 'in-queue']);
});

Route::prefix('/game')->name('game.')->controller(GameController::class)->group(function () {
    Route::get('/{id}', 'index')->name('index')->where('id', '[0-9a-fA-F\-]{36}');
    Route::post('/place', 'place')->name('place')/*->middleware('in-game')*/;
    Route::post('/pre/update', 'preUpdate')->name('pre-update')->middleware('auth');
    Route::post('/update', 'update')->name('update')->middleware('auth');
    Route::get('/', 'template')->name('index.template');
});

Route::prefix('/auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginPage')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login')->middleware('guest');
    Route::delete('/logout', 'logout')->name('logout')->middleware('auth');
});


//

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

