@php
    use App\Models\Game;
    use App\Enums\GameStatus;

    $active_games = function () { return Game::where('status', GameStatus::InProgress)->count(); }

@endphp

<x-layout title="Accueil - Puissance 4" :css="asset('css/queue/style.css')">
    <x-header :user="$user"/>

    <main class="main-content">
        <div class="waiting-container">
            <div class="loading-section">
                <div class="spinner-container">
                    <div class="spinner"></div>
                    <div class="spinner-inner"></div>
                    <div class="spinner-center">⚡</div>
                </div>
            </div>

            <!-- Status -->
            <div class="status-section">
                <h1 class="status-title">Recherche en cours<span class="dots"></span></h1>
                <p class="status-message" id="status-message">
                    Nous recherchons un adversaire pour vous. Cela ne devrait pas prendre longtemps !
                </p>

                <div class="status-details">
                    <div class="detail-item">
                        <div class="detail-label">Temps d'attente</div>
                        <div class="detail-value timer" id="waiting-time">00:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Parties actives</div>
                        <div class="detail-value" id="active-games">{{ $active_games() }}</div>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="progress-section">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div class="progress-text">Recherche d'un adversaire de niveau similaire...</div>
            </div>

            <!-- Players (si applicable) -->
            <div class="players-section" id="players-section">
                <h3 class="players-title">Joueurs dans la partie</h3>
                <div class="players-list">
                    <div id="player-1" class="player-item">
                        <div class="player-info">
                            <div class="player-avatar">{{ $user->username[0]  }}</div>
                            <span class="player-name" id="current-player">{{ $user->username }}</span>
                        </div>
                        <span class="player-status waiting">En attente</span>
                    </div>
                    <div id="player-2" class="player-item">
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
                <button id="btn-accept" class="btn btn-success btn-large d-none" onclick="acceptGame()">
                    <span>✓</span>
                    Accepter
                </button>
                <button id="btn-cancel" class="btn btn-danger btn-large" onclick="cancelWaiting()">
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

    <script>
        // Variables globales
        let waitingStartTime = Date.now();
        let waitingTimer;
        let messageTimeout;
        let messageInterval;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function () {
            startWaitingTimer();
            simulateWaitingProcess();
            loadOnlineStats();
        });

        // Timer d'attente
        function startWaitingTimer() {
            waitingTimer = setInterval(() => {
                const elapsed = Date.now() - waitingStartTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);

                document.getElementById('waiting-time').textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
        }

        // Simulation du processus d'attente
        function simulateWaitingProcess() {
            const messages = [
                "Recherche d'un adversaire...",
                "Adversaire trouvé ! Préparation de la partie...",
                "En attente des joueurs..."
            ];

            let currentMessage = 0;

            messageInterval = setInterval(() => {
                if (currentMessage < messages.length) {
                    document.getElementById('status-message').textContent = messages[currentMessage];

                    if (currentMessage >= 1) {
                        updatePlayerFound();
                    }

                    currentMessage++;
                } else {
                    clearInterval(messageInterval);
                    // Redirection vers la partie après 3 secondes
                    // setTimeout(() => {
                    //     window.location.href = '/game';
                    // }, 3000);
                }
            }, 5000);
        }

        // Mettre à jour quand un joueur est trouvé
        function updatePlayerFound() {
            const player1 = document.getElementById('player-1');
            const player2 = document.getElementById('player-2');

            const p1Status = player1.querySelector('.player-status');
            p1Status.innerText = 'Pas Prêt'
            p1Status.classList.remove('waiting');
            p1Status.classList.add('not-ready');

            player2.querySelector('.player-avatar').innerText = '{{ $opponent->username[0] }}';
            player2.querySelector('.player-name').innerText = '{{ $opponent->username }}';

            const p2Status = player2.querySelector('.player-status');
            p2Status.innerText = 'Prêt'
            p2Status.classList.add('ready');

            document.getElementById('btn-accept').classList.remove('d-none');

            showMessage("Adversaire trouvé ! Acceptez la partie pour jouer...", "success");
        }

        // Charger les statistiques en ligne
        function loadOnlineStats() {
            setInterval(() => {
                document.getElementById('active-games').textContent = {{ $active_games()  }};
            }, 5000);
        }

        // Accepter partie
        function acceptGame() {
            const p1Status = document.querySelector('#player-1 .player-status');
            p1Status.innerText = 'Prêt'
            p1Status.classList.remove('not-ready');
            p1Status.classList.add('ready')

            showMessage("Joueurs prêts, la partie va commencer...", "success");

            // create game

            setTimeout(() => {
                window.location.href = '{{ route('game.index', ['id' => 1]) }}';
            }, 1500);
        }

        // Annuler l'attente
        function cancelWaiting() {
            if (confirm('Êtes-vous sûr de vouloir annuler la recherche ?')) {
                clearInterval(waitingTimer);

                window.location.href = '{{ route('index') }}';

                /* Vraie implémentation :
                fetch('/cancel-waiting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/home';
                    }
                });
                */
            }
        }

        // Afficher un message
        function showMessage(text, type = "info") {
            const messageSection = document.getElementById('message-section');
            const messageText = document.getElementById('message-text');

            messageText.textContent = text;
            messageSection.className = `message-section show ${type}`;

            // Cacher le message après 5 secondes
            clearTimeout(messageTimeout);
            messageTimeout = setTimeout(() => {
                messageSection.classList.remove('show');
            }, 5000);
        }

        // Nettoyage avant fermeture de la page
        window.addEventListener('beforeunload', function () {
            clearInterval(waitingTimer);
            clearTimeout(messageTimeout);
            clearTimeout(messageInterval);
        });
    </script>
</x-layout>
