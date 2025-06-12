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

    <div class="alert alert-error" id="error-message">
        <!-- Messages d'erreur -->
    </div>

    <div class="alert alert-success" id="success-message">
        <!-- Messages de succès -->
    </div>

    <form id="login-form" method="POST" action="/login">
        <!-- Token CSRF pour Laravel -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

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

        <div class="form-options">
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>
            <a href="/forgot-password" class="forgot-password">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn btn-primary" id="login-btn">
            <span class="btn-text">Se connecter</span>
            <div class="loading">
                <div class="spinner"></div>
                <span>Connexion...</span>
            </div>
        </button>
    </form>

    <div class="divider">
        <span>ou</span>
    </div>

    <button type="button" class="btn btn-secondary" onclick="window.location.href='/register'">
        Créer un compte
    </button>

    <div class="footer-links">
        <a href="/about">À propos</a>
        <a href="/help">Aide</a>
        <a href="/privacy">Confidentialité</a>
    </div>
</div>

<script>
    // Gestion du formulaire de connexion
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const loginBtn = document.getElementById('login-btn');
        const btnText = loginBtn.querySelector('.btn-text');
        const loading = loginBtn.querySelector('.loading');
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');

        // Cacher les messages précédents
        hideMessages();

        // Validation côté client
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        if (!username || !password) {
            showError('Veuillez remplir tous les champs.');
            return;
        }

        if (username.length < 3) {
            showError('Le pseudo doit contenir au moins 3 caractères.');
            return;
        }

        if (password.length < 6) {
            showError('Le mot de passe doit contenir au moins 6 caractères.');
            return;
        }

        // Afficher le loading
        btnText.style.display = 'none';
        loading.style.display = 'flex';
        loginBtn.disabled = true;

        // Simulation d'une requête (remplacez par votre logique Laravel)
        setTimeout(() => {
            // Ici vous feriez la vraie requête vers Laravel
            simulateLogin(username, password);
        }, 1500);
    });

    // Simulation de connexion (à remplacer par la vraie logique)
    function simulateLogin(username, password) {
        const loginBtn = document.getElementById('login-btn');
        const btnText = loginBtn.querySelector('.btn-text');
        const loading = loginBtn.querySelector('.loading');

        // Réinitialiser le bouton
        btnText.style.display = 'inline';
        loading.style.display = 'none';
        loginBtn.disabled = false;

        // Simulation de différents cas
        if (username === 'admin' && password === 'password') {
            showSuccess('Connexion réussie ! Redirection...');
            setTimeout(() => {
                window.location.href = '/game'; // Redirection vers le jeu
            }, 1500);
        } else {
            showError('Pseudo ou mot de passe incorrect.');
        }
    }

    // Vraie implémentation avec Laravel (décommentez et adaptez)
    /*
    function submitLogin(formData) {
        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                username: formData.get('username'),
                password: formData.get('password'),
                remember: formData.get('remember') ? true : false
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Connexion réussie ! Redirection...');
                setTimeout(() => {
                    window.location.href = data.redirect || '/game';
                }, 1500);
            } else {
                showError(data.message || 'Erreur de connexion.');
            }
        })
        .catch(error => {
            showError('Erreur de connexion. Veuillez réessayer.');
            console.error('Error:', error);
        })
        .finally(() => {
            // Réinitialiser le bouton
            const loginBtn = document.getElementById('login-btn');
            const btnText = loginBtn.querySelector('.btn-text');
            const loading = loginBtn.querySelector('.loading');

            btnText.style.display = 'inline';
            loading.style.display = 'none';
            loginBtn.disabled = false;
        });
    }
    */

    // Fonctions utilitaires pour les messages
    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';

        // Scroll vers le message
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showSuccess(message) {
        const successDiv = document.getElementById('success-message');
        successDiv.textContent = message;
        successDiv.style.display = 'block';

        // Scroll vers le message
        successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideMessages() {
        document.getElementById('error-message').style.display = 'none';
        document.getElementById('success-message').style.display = 'none';
    }

    // Gestion de l'affichage/masquage du mot de passe (optionnel)
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('toggle-password');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            toggleBtn.textContent = '👁️';
        }
    }

    // Auto-focus sur le premier champ
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('username').focus();
    });

    // Gestion de la touche Entrée
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('login-form').dispatchEvent(new Event('submit'));
        }
    });
</script>
</body>
</html>
