<x-layout title="Accueil - Puissance 4" :css="asset('css/style.css')">
    <x-header/>

    <main class="main-content">
        <div class="hero-section">
            <h1 class="hero-title">PUISSANCE 4</h1>
            <p class="hero-subtitle">
                Défiez vos amis dans des parties palpitantes de Puissance 4.
                Alignez quatre jetons et remportez la victoire !
            </p>
        </div>

        <div class="play-section">
            <button class="play-btn" id="play-btn">
                <span class="play-icon">▶</span>
                JOUER
            </button>
        </div>

        @auth()
            @php $stats = Auth::user()->stats(); @endphp
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-number" id="games-played" data-value="{{ $stats['played'] }}">0</div>
                    <div class="stat-label">Parties jouées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="games-won" data-value="{{ $stats['wins'] }}">0</div>
                    <div class="stat-label">Victoires</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="win-rate" data-value="{{ $stats['win_rate'] }}">0%</div>
                    <div class="stat-label">Taux de victoire</div>
                </div>
            </div>
        @endauth

        <!-- Actions rapides -->
        <!-- <div class="quick-actions">
            <a href="/profile" class="action-btn">
                <span>👤</span>
                Mon Profil
            </a>
            <a href="/leaderboard" class="action-btn">
                <span>🏆</span>
                Classement
            </a>
            <a href="/history" class="action-btn">
                <span>📊</span>
                Historique
            </a>
            <a href="/settings" class="action-btn">
                <span>⚙️</span>
                Paramètres
            </a>
        </div> -->
    </main>

    <x-footer/>

    <x-variables/>
    @vite('resources/js/scripts/main.js')
</x-layout>

