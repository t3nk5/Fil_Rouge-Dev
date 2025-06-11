<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Events\TestPusherEvent;


Route::get('/', [GameController::class, 'showGame']);

Route::get('/init', [GameController::class, 'initGrid']);

Route::get('/play/{column}', [GameController::class, 'play']);

Route::get('/restart', [GameController::class, 'restart'])->name('restart');



Route::get('/pusher-test', function () {
    return view('pusher-test');
});

Route::get('/send-test', function () {
    event(new TestPusherEvent('Message envoyé via Laravel'));
    return 'Event sent!';
});

