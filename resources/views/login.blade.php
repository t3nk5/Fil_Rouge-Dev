<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Puissance 4</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="login-container">
    <div class="logo">
        <h1>PUISSANCE 4</h1>
        <p>Connectez-vous pour jouer</p>
    </div>


    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="login-form" method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label for="username">Pseudo</label>
            <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                placeholder="Entrez votre pseudo"
                required
                autocomplete="username"
                value="{{ old('username') }}"
            >
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="Entrez votre mot de passe"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            <span class="btn-text">Se connecter</span>
        </button>
    </form>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('username').focus();
    });
</script>


</body>
</html>
