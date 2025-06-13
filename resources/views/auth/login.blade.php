<x-layout title="Connexion - Puissance 4" :css="asset('css/auth/style.css')">
    <main class="login-container">
        <div class="login-logo">
            <h1>PUISSANCE 4</h1>
            <p>Connectez-vous pour jouer</p>
        </div>

        @error("login")
        <div class="alert alert-error" id="error-message">
            {{ $message }}
        </div>
        @enderror

        <form id="login-form" method="post" action="{{ route('auth.login') }}">
            @csrf

            <div class="form-group">
                <label for="name">Pseudo</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    placeholder="Entrez votre pseudo"
                    required
                    autocomplete="name"
                    value="{{ old('name') }}"
                >
                @error("name")
                <div class="input-error" id="name-error-message">
                    {{ $message }}
                </div>
                @enderror
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
                @error("password")
                <div class="input-error" id="password-error-message">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" id="login-btn">
                <span class="btn-text">Se connecter</span>
            </button>
        </form>
    </main>

    <script>
        /*function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }*/

        // Auto-focus sur le premier champ
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name').focus();
        });
    </script>

</x-layout>
