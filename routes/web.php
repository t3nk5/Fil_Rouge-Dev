<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::prefix('/queue')->name('queue.')->controller(QueueController::class)->group(function () {
    Route::get('/', 'index')->name('index')->middleware(['auth', 'in-queue']);
    Route::post('/join', 'join')->name('join')->middleware('auth');
    Route::post('/leave', 'leave')->name('leave')->middleware(['auth', 'in-queue']);
});

Route::prefix('/game')->name('game.')->controller(GameController::class)->group(function () {
    Route::get('/{id}', 'index')->name('index')->where('id', '[0-9a-fA-F\-]{36}');
    Route::post('/place', 'place')->name('place')->middleware('in-game');
    Route::post('/pre/update', 'preUpdate')->name('pre-update')->middleware(['auth', 'in-queue']);
    Route::post('/update', 'update')->name('update');
    Route::get('/', fn () => redirect()->route('index'))->name('index.template');
});

Route::prefix('/auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::view('/login', 'auth.login')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login')->middleware('guest');
    Route::delete('/logout', 'logout')->name('logout')->middleware('auth');
});
