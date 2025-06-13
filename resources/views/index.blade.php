<x-layout title="Accueil - Puissance 4" :css="asset('css/style.css')">
    <x-header :user="$user"/>

    <main class="main-content">
        <div class="hero-section">
            <h1 class="hero-title">PUISSANCE 4</h1>
            <p class="hero-subtitle">
                Défiez vos amis dans des parties palpitantes de Puissance 4.
                Alignez quatre jetons et remportez la victoire !
            </p>
        </div>

        <div class="play-section">
            <button class="play-btn" onclick="playGame()" id="play-btn">
                <span class="play-icon">▶</span>
                JOUER
            </button>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number" id="games-played">0</div>
                <div class="stat-label">Parties jouées</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="games-won">0</div>
                <div class="stat-label">Victoires</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="win-rate">0%</div>
                <div class="stat-label">Taux de victoire</div>
            </div>
        </div>

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

    <script>
        // Charger les statistiques
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                animateCounter('games-played', {{ $user->gamesPlayed }});
                animateCounter('games-won', {{ $user->gamesWon }});
                animateCounter('win-rate', {{ $user->winRate }}, '%');
            }, 500);
        });

        // Animation des compteurs
        function animateCounter(elementId, targetValue, suffix = '') {
            const element = document.getElementById(elementId);
            let currentValue = 0;
            const increment = targetValue / 30;

            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= targetValue) {
                    currentValue = targetValue;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(currentValue) + suffix;
            }, 50);
        }

        // Démarrer une partie
        function playGame() {
            const playBtn = document.getElementById('play-btn');
            const playIcon = playBtn.querySelector('.play-icon');

            // État de chargement
            playBtn.classList.add('loading');
            playIcon.textContent = '⟳';

            setTimeout(() => {
                window.location.href = '{{ route('queue') }}';

                playBtn.classList.remove('loading');
                playIcon.textContent = '▶';
            }, 500);
        }

        // Déconnexion
        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Simulation - remplacez par votre logique Laravel
                window.location.href = '/logout';

                /* Vraie implémentation Laravel :
                fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/login';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la déconnexion:', error);
                });
                */
            }
        }

        // Gestion des raccourcis clavier
        document.addEventListener('keydown', function (e) {
            // Espace ou Entrée pour jouer
            if (e.code === 'Space' || e.code === 'Enter') {
                e.preventDefault();
                playGame();
            }

            // Échap pour déconnexion
            // if (e.code === 'Escape') {
            //     logout();
            // }
        });

        // Mise à jour en temps réel des statistiques (avec Pusher)
        /*
        const pusher = new Pusher('YOUR_APP_KEY', {
            cluster: 'YOUR_CLUSTER'
        });

        const channel = pusher.subscribe('user-stats');

        channel.bind('stats-updated', function(data) {
            if (data.userId === getCurrentUserId()) {
                updateStats(data.stats);
            }
        });

        function updateStats(stats) {
            document.getElementById('games-played').textContent = stats.gamesPlayed;
            document.getElementById('games-won').textContent = stats.gamesWon;
            document.getElementById('win-rate').textContent = stats.winRate + '%';
        }
        */
    </script>
</x-layout>

