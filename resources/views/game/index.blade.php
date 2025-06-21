<x-layout title="Jeu - Puissance 4" :css="asset('css/game/style.css')">
    <x-header/>

    <main class="main-content" id="game" data-game-id="{{ $game_id }}">
        <div class="game-info">
            <div class="player-info" id="player-1">
                <div class="player-indicator red"></div>
                <span></span>
            </div>
            <div class="game-status" id="game-status"></div>
            <div class="player-info" id="player-2">
                <div class="player-indicator yellow"></div>
                <span></span>
            </div>
        </div>

        <div class="game-board">
            <div class="grid" id="game-grid"></div>
        </div>

        <div class="message" id="game-message"></div>

        <div class="controls">
            <button id="btn-leave-game" class="btn btn-primary d-none">Quitter la partie</button>
        </div>

    </main>

    <x-footer/>

    <x-variables/>
    @vite('resources/js/scripts/game.js')
</x-layout>
