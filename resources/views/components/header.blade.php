<header class="header">
    <a class="logo" href="{{ route('index') }}">
        <div class="logo-icon">P4</div>
        <h1>PUISSANCE 4</h1>
    </a>

    <div class="user-info">
        <div>
            <span class="welcome-text">Bienvenue,</span>
            <span class="username" id="username">{{ $user->username }}</span>
        </div>
        <button class="logout-btn" onclick="logout()">
            <span>🚪</span>
            Déconnexion
        </button>
    </div>
</header>
