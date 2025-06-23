@php use App\Models\Game; use App\Enums\GameStatus; @endphp
<x-layout title="Accueil - Puissance 4" :css="asset('css/queue/style.css')">
    <x-header/>

    <main class="main-content">
        <div class="waiting-container">
            <div class="loading-section">
                <div class="spinner-container">
                    <div class="spinner"></div>
                    <div class="spinner-inner"></div>
                    <div class="spinner-center">⚡</div>
                </div>
            </div>

            <div class="status-section">
                <h1 class="status-title">Recherche en cours<span class="dots"></span></h1>

                <div class="status-details">
                    <div class="detail-item">
                        <div class="detail-label">Temps d'attente</div>
                        <div class="detail-value timer" id="waiting-time">00:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Parties actives</div>
                        <div class="detail-value"
                             id="active-games">{{ Game::where('status', GameStatus::InProgress)->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="progress-section">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <div class="players-section" id="players-section">
                <h3 class="players-title">Joueurs dans la partie</h3>
                <div class="players-list">
                    <div id="player-1" class="player-item"
                         data-queue-id="{{ Auth::user()->queue->id }}"
                         data-game-id="{{ Auth::user()->queue->gamePlayer->game->id }}"
                    >
                        <div class="player-info">
                            <div class="player-avatar">{{ Auth::user()->name[0] }}</div>
                            <span class="player-name" id="current-player">{{ Auth::user()->name }}</span>
                        </div>
                        <span class="player-status"></span>
                    </div>
                    <div id="player-2" class="player-item d-none">
                        <div class="player-info">
                            <div class="player-avatar">?</div>
                            <span class="player-name">En attente...</span>
                        </div>
                        <span class="player-status">Recherche</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="actions-section">
                <button id="btn-ready" class="btn btn-success btn-large d-none">
                    <span>✓</span>
                    Prêt
                </button>
                <button id="btn-not-ready" class="btn btn-danger-transparent btn-large d-none">
                    <span>✕</span>
                    Pas Prêt
                </button>
                <button id="btn-cancel" class="btn btn-danger btn-large">
                    <span>✕</span>
                    Annuler
                </button>
            </div>

            <!-- Messages -->
            <div class="message-section" id="message-section">
                <p id="message-text"></p>
            </div>
        </div>
    </main>

    <x-footer/>

    <x-variables/>
    @vite('resources/js/scripts/queue.js')
</x-layout>
