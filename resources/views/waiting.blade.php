<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En attente - Puissance 4</title>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="{{ asset('css/waiting.css') }}">
</head>
<body>
<!-- Header -->
<header class="header">
    <div class="logo">
        <div class="logo-icon">P4</div>
        <h1>PUISSANCE 4</h1>
    </div>

    <div class="header-actions">
        <a href="/home" class="btn btn-secondary">
            <span>🏠</span>
            Accueil
        </a>
        <button class="btn btn-danger" onclick="cancelWaiting()">
            <span>✕</span>
            Annuler
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <div class="waiting-container">
        <div class="loading-section">
            <div class="spinner-container">
                <div class="spinner"></div>
                <div class="spinner-inner"></div>
                <div class="spinner-center">⚡</div>
            </div>
        </div>

        <section class="status-section" aria-live="polite">
            <h2 class="status-title">Recherche de partie <span class="dots"></span></h2>
            <p class="status-message">En attente d’un adversaire, merci de patienter...</p>
            <div class="status-details">
                <div class="detail-item">
                    <div class="detail-label">Joueurs</div>
                    <div class="detail-value">2/2</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Temps écoulé</div>
                    <div class="detail-value timer" id="timer">00:00</div>
                </div>
            </div>
        </section>

        <section class="progress-section" aria-hidden="true">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div class="progress-text">Chargement en cours...</div>
        </section>

        <section class="players-section" aria-label="Liste des joueurs">
            <h3 class="players-title">Joueurs dans la file d'attente</h3>
            <div class="players-list" id="players-list">
                <div class="player-item">
                    <div class="player-info">
                        <div class="player-avatar">A</div>
                        <div class="player-name">Alice</div>
                    </div>
                    <div class="player-status waiting">En attente</div>
                </div>
                <div class="player-item">
                    <div class="player-info">
                        <div class="player-avatar">B</div>
                        <div class="player-name">Bob</div>
                    </div>
                    <div class="player-status waiting">En attente</div>
                </div>
            </div>
        </section>

        <section class="actions-section">
            <button class="btn btn-danger btn-large" onclick="cancelWaiting()">Annuler la recherche</button>
        </section>

        <section class="message-section" id="message-section" role="alert" aria-live="assertive"></section>
    </div>
</main>
<h3>Joueurs en attente :</h3>
<ul>
    @foreach ($waitingPlayers as $player)
        <li>{{ $player->session_id }}</li>
    @endforeach
</ul>
<form method="POST" action="{{ route('queue.leave') }}">
    @csrf
    <button type="submit" style="padding: 10px 20px; margin-top: 20px;">Quitter la file d’attente</button>
</form>

<script>
    window.addEventListener('beforeunload', function (e) {
        navigator.sendBeacon('/queue/leave');
    });
</script>

@if(session('redirect_to_game'))
    <script>
        window.location.href = "{{ session('redirect_to_game') }}";
    </script>
@endif

<script>
    setTimeout(() => {
        window.location.reload();
    }, 3000);
</script>
<script>
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    };
</script>

<script>
    let startTime = Date.now();

    function updateTimer() {
        const elapsed = Date.now() - startTime;
        const seconds = Math.floor(elapsed / 1000) % 60;
        const minutes = Math.floor(elapsed / (1000 * 60));
        const formatted = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
        document.getElementById('timer').textContent = formatted;
    }

    setInterval(updateTimer, 1000);
    updateTimer();

    function cancelWaiting() {
        const messageSection = document.getElementById('message-section');
        messageSection.textContent = "La recherche a été annulée.";
        messageSection.classList.add('show');

        const buttons = document.querySelectorAll('button');
        buttons.forEach(btn => btn.disabled = true);

        setTimeout(() => {
            window.location.href = "/";
        }, 2000);
    }
</script>


</body>
</html>
