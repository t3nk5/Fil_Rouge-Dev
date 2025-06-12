<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En attente - Puissance 4</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo h1 {
            font-size: 1.8rem;
            font-weight: 300;
            color: #34495e;
            letter-spacing: 1px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #ecf0f1;
            color: #2c3e50;
        }

        .btn-secondary:hover {
            background: #d5dbdb;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }

        .waiting-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            animation: fadeInUp 0.6s ease-out;
        }

        /* Loading Animation */
        .loading-section {
            margin-bottom: 2rem;
        }

        .spinner-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
        }

        .spinner {
            width: 100%;
            height: 100%;
            border: 4px solid #ecf0f1;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }

        .spinner-inner {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 80px;
            height: 80px;
            border: 3px solid #f8f9fa;
            border-bottom: 3px solid #2980b9;
            border-radius: 50%;
            animation: spin 1s linear infinite reverse;
        }

        .spinner-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
        }

        /* Status */
        .status-section {
            margin-bottom: 2rem;
        }

        .status-title {
            font-size: 1.8rem;
            font-weight: 300;
            color: #34495e;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .status-message {
            font-size: 1.1rem;
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .status-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-size: 1.2rem;
            font-weight: 500;
            color: #2c3e50;
        }

        .timer {
            color: #3498db;
            font-weight: 600;
        }

        /* Progress Bar */
        .progress-section {
            margin-bottom: 2rem;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #ecf0f1;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2980b9);
            border-radius: 3px;
            animation: progress 3s ease-in-out infinite;
        }

        @keyframes progress {
            0% { width: 20%; }
            50% { width: 80%; }
            100% { width: 20%; }
        }

        .progress-text {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        /* Players List */
        .players-section {
            margin-bottom: 2rem;
        }

        .players-title {
            font-size: 1.2rem;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .players-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .player-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .player-avatar {
            width: 32px;
            height: 32px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .player-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .player-status {
            font-size: 0.8rem;
            color: #27ae60;
            font-weight: 500;
        }

        .player-status.waiting {
            color: #f39c12;
        }

        /* Actions */
        .actions-section {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-large {
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 8px;
        }

        /* Messages */
        .message-section {
            margin-top: 2rem;
            padding: 1rem;
            background: #d6eaf8;
            border: 1px solid #3498db;
            border-radius: 8px;
            color: #2c3e50;
            font-size: 0.9rem;
            display: none;
        }

        .message-section.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .waiting-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .status-details {
                flex-direction: column;
                gap: 1rem;
            }

            .actions-section {
                flex-direction: column;
            }

            .btn-large {
                width: 100%;
            }

            .spinner-container {
                width: 100px;
                height: 100px;
            }

            .spinner-inner {
                top: 15px;
                left: 15px;
                width: 70px;
                height: 70px;
            }
        }

        /* Dots animation */
        .dots {
            display: inline-block;
        }

        .dots::after {
            content: '';
            animation: dots 2s infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
    </style>
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
        <!-- Loading Animation -->
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
                    <div class="detail-label">Joueurs en ligne</div>
                    <div class="detail-value" id="online-players">42</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Parties actives</div>
                    <div class="detail-value" id="active-games">15</div>
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
        <div class="players-section" id="players-section" style="display: none;">
            <h3 class="players-title">Joueurs dans la partie</h3>
            <div class="players-list" id="players-list">
                <div class="player-item">
                    <div class="player-info">
                        <div class="player-avatar">J</div>
                        <span class="player-name" id="current-player">Joueur123</span>
                    </div>
                    <span class="player-status">Prêt</span>
                </div>
                <div class="player-item">
                    <div class="player-info">
                        <div class="player-avatar">?</div>
                        <span class="player-name">En attente...</span>
                    </div>
                    <span class="player-status waiting">Recherche</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-section">
            <button class="btn btn-secondary btn-large" onclick="changePreferences()">
                <span>⚙️</span>
                Préférences
            </button>
            <button class="btn btn-danger btn-large" onclick="cancelWaiting()">
                <span>✕</span>
                Annuler l'attente
            </button>
        </div>

        <!-- Messages -->
        <div class="message-section" id="message-section">
            <p id="message-text"></p>
        </div>
    </div>
</main>

<script>
    // Variables globales
    let waitingStartTime = Date.now();
    let waitingTimer;
    let messageTimeout;

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
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
            "Vérification des préférences de jeu...",
            "Connexion aux serveurs de jeu...",
            "Adversaire trouvé ! Préparation de la partie...",
            "Lancement de la partie dans quelques secondes..."
        ];

        let currentMessage = 0;

        const messageInterval = setInterval(() => {
            if (currentMessage < messages.length) {
                document.getElementById('status-message').textContent = messages[currentMessage];

                // Afficher la section joueurs à partir du 4ème message
                if (currentMessage >= 3) {
                    document.getElementById('players-section').style.display = 'block';
                    updatePlayerFound();
                }

                currentMessage++;
            } else {
                clearInterval(messageInterval);
                // Redirection vers la partie après 3 secondes
                setTimeout(() => {
                    window.location.href = '/game';
                }, 3000);
            }
        }, 3000);
    }

    // Mettre à jour quand un joueur est trouvé
    function updatePlayerFound() {
        const playersList = document.getElementById('players-list');
        playersList.innerHTML = `
                <div class="player-item">
                    <div class="player-info">
                        <div class="player-avatar">J</div>
                        <span class="player-name">Joueur123</span>
                    </div>
                    <span class="player-status">Prêt</span>
                </div>
                <div class="player-item">
                    <div class="player-info">
                        <div class="player-avatar">A</div>
                        <span class="player-name">Adversaire42</span>
                    </div>
                    <span class="player-status">Prêt</span>
                </div>
            `;

        showMessage("Adversaire trouvé ! La partie va commencer...", "success");
    }

    // Charger les statistiques en ligne
    function loadOnlineStats() {
        // Simulation - remplacez par vos vraies données
        setInterval(() => {
            const onlinePlayers = Math.floor(Math.random() * 20) + 35;
            const activeGames = Math.floor(Math.random() * 10) + 10;

            document.getElementById('online-players').textContent = onlinePlayers;
            document.getElementById('active-games').textContent = activeGames;
        }, 5000);
    }

    // Annuler l'attente
    function cancelWaiting() {
        if (confirm('Êtes-vous sûr de vouloir annuler la recherche ?')) {
            clearInterval(waitingTimer);

            // Simulation - remplacez par votre logique Laravel
            window.location.href = '/home';

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

    // Changer les préférences
    function changePreferences() {
        showMessage("Fonctionnalité bientôt disponible !", "info");
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

    // Gestion des raccourcis clavier
    document.addEventListener('keydown', function(e) {
        if (e.code === 'Escape') {
            cancelWaiting();
        }
    });

    // Intégration Pusher pour les mises à jour en temps réel
    /*
    const pusher = new Pusher('YOUR_APP_KEY', {
        cluster: 'YOUR_CLUSTER'
    });

    const channel = pusher.subscribe('matchmaking');

    channel.bind('player-found', function(data) {
        updatePlayerFound();
        showMessage("Adversaire trouvé ! Préparation de la partie...", "success");
    });

    channel.bind('game-ready', function(data) {
        window.location.href = `/game/${data.gameId}`;
    });
    */

    // Nettoyage avant fermeture de la page
    window.addEventListener('beforeunload', function() {
        clearInterval(waitingTimer);
        clearTimeout(messageTimeout);
    });
</script>
</body>
</html>
