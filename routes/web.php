<?php

use App\Http\Controllers\GameMoveController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;



Route::get('/home', [GameController::class, 'showGame']);

Route::get('/init', [GameController::class, 'initGrid']);

Route::get('/play/{column}', [GameController::class, 'play']);

Route::get('/restart', [GameController::class, 'restart'])->name('restart');

Route::get('/users', [UserController::class, 'home']);

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'username' => 'required|string|min:3',
        'password' => 'required|string|min:4',
    ]);



    $user = User::where('name', $validated['username'])->first();

    if (!$user) {
        $user = User::create([
            'name' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);
    }

    auth()->login($user);

    return redirect('/');
});

Route::get('/login', function () {
    return view('login');
});



Route::post('/games/{game}/move/{column}', [GameMoveController::class, 'store'])->middleware('auth');











Route::get('/', [GameController::class, 'home'])->name('index');



Route::get('/waiting', [GameController::class, 'waiting'])->middleware('auth')->name('waiting');



Route::get('/game', [GameController::class, 'game'])->name('game');
