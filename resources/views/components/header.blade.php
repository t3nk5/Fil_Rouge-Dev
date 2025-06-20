<header class="header">
    <a class="logo" href="{{ route('index') }}">
        <div class="logo-icon">P4</div>
        <h1>PUISSANCE 4</h1>
    </a>

    <div class="user-info">
        @auth
            <div>
                <span class="welcome-text">Bienvenue,</span>
                <span class="username" id="username" data-user-id="{{ Auth::user()->id }}">{{ Auth::user()->name }}</span>
            </div>
        <form action="{{ route('auth.logout') }}" method="post">
            @method('delete')
            @csrf
            <button class="logout-btn" >Déconnexion</button>
        </form>
        @endauth
        @guest
            <a href="{{ route('auth.login') }}" class="logout-btn">
                Connexion
            </a>
        @endguest
    </div>
</header>
