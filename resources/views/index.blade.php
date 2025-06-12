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
            <span class="username" id="username">{{ auth()->user()->name ?? 'Joueur' }}</span>

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

    document.addEventListener('DOMContentLoaded', function() {

        loadUserStats();
    });





    function loadUserStats() {
        // a changer
        setTimeout(() => {
            animateCounter('games-played', 15);
            animateCounter('games-won', 9);
            animateCounter('win-rate', 60, '%');
        }, 500);
    }

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


    function startGame() {
        window.location.href = '/waiting';
    }

    function logout() {
        if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {

            //changer pour de deco
            window.location.href = '/logout';


        }
    }


    document.addEventListener('keydown', function(e) {

        if (e.code === 'Space' || e.code === 'Enter') {
            e.preventDefault();
            startGame();
        }


        if (e.code === 'Escape') {
            logout();
        }
    });


</script>
</body>
</html>
