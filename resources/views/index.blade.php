<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Puissance 4</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
<!-- Header -->
<header class="header">
    <div class="logo">
        <div class="logo-icon">P4</div>
        <h1>PUISSANCE 4</h1>
    </div>

    <div class="user-info">
        <div>
            <span class="welcome-text">Bienvenue,</span>
            <span class="username" id="username">Joueur</span>
        </div>
        <button class="logout-btn" onclick="logout()">
            <span>🚪</span>
            Déconnexion
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <div class="hero-section">
        <h1 class="hero-title">PUISSANCE 4</h1>
        <p class="hero-subtitle">
            Défiez vos amis dans des parties palpitantes de Puissance 4.
            Alignez quatre jetons et remportez la victoire !
        </p>
    </div>

    <div class="play-section">
        <button class="play-btn" onclick="startGame()" id="play-btn">
            <span class="play-icon">▶</span>
            JOUER
        </button>
    </div>

    <!-- Statistiques -->
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
    <div class="quick-actions">
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
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <p>
        &copy; 2024 Puissance 4.
        <a href="/about">À propos</a>
        <a href="/help">Aide</a>
        <a href="/privacy">Confidentialité</a>
    </p>
</footer>

<script>
    // Initialisation de la page
    document.addEventListener('DOMContentLoaded', function() {
        loadUserData();
        loadUserStats();
    });

    // Charger les données utilisateur
    function loadUserData() {
        // Simulation - remplacez par votre logique Laravel
        const userData = {
            username: 'Joueur123', // Récupéré depuis la session Laravel
            gamesPlayed: 15,
            gamesWon: 9,
            winRate: 60
        };

        document.getElementById('username').textContent = userData.username;
    }

    // Charger les statistiques
    function loadUserStats() {
        // Simulation - remplacez par une requête AJAX vers Laravel
        setTimeout(() => {
            animateCounter('games-played', 15);
            animateCounter('games-won', 9);
            animateCounter('win-rate', 60, '%');
        }, 500);
    }

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
    function startGame() {
        const playBtn = document.getElementById('play-btn');
        const playIcon = playBtn.querySelector('.play-icon');

        // État de chargement
        playBtn.classList.add('loading');
        playIcon.textContent = '⟳';

        // Simulation de recherche de partie
        setTimeout(() => {
            // Redirection vers la page de jeu
            window.location.href = '/game';
        }, 1500);
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
    document.addEventListener('keydown', function(e) {
        // Espace ou Entrée pour jouer
        if (e.code === 'Space' || e.code === 'Enter') {
            e.preventDefault();
            startGame();
        }

        // Échap pour déconnexion
        if (e.code === 'Escape') {
            logout();
        }
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
</body>
</html>
