<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginPage(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        $user = User::where('name', $credentials['name'])->first();

        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended(route('index'));
            }

            return to_route('auth.login')->withErrors([
                'login' => "Nom d'utilisateur déja utilisé ou mot de passe incorrect.",
            ])->onlyInput('name');
        }

        $newUser = User::create([
            'name' => $credentials['name'],
            'password' => Hash::make($credentials['password']),
        ]);

        Auth::login($newUser);
        $request->session()->regenerate();
        return redirect()->intended(route('index'));
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return to_route('index');
    }
}
