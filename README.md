# 🎮 Serveur de Matchmaking – Projet Laravel

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)

---

## 🧠 Présentation du projet

Ce projet est une **implémentation d’un serveur de matchmaking** en temps réel pour des jeux multijoueurs.  
Il permet de gérer une file d’attente de joueurs, les associer automatiquement entre eux et lancer une partie dès que les conditions sont réunies.  
Ce projet s'inscrit dans le cadre du **Projet Dev** de l'année et a été réalisé à l’aide du framework **Laravel**.

---

## 🚀 Installation & Lancement

### 🧾 1. Cloner le projet

```bash
  git clone https://github.com/t3nk5/Fil_Rouge-Dev.git
  cd Fil_Rouge-Dev
```

### 📦 2. Installer les dépendances

```bash
  composer install
  npm install
```

### ⚙️ 3. Configuration de l'environnement

- Copier le fichier `.env.example` en `.env` :
```bash
  cp .env.example .env
```

- Générer la clé de l’application :
```bash
  php artisan key:generate
```

### 🔌 4. Installer Laravel Reverb
```bash
  php artisan reverb:install
```
> ⚠️ **Pensez à supprimer les clés Reverb en double dans le `.env`** après l'installation.

### 🗄️ 5. Initialiser la base de données
- Lancer les migrations :
```bash
  php artisan migrate
```
> Si la base de données n'existe pas, Laravel vous proposera de la créer. Répondez **oui**.

---

### ▶️ 6. Démarrer le serveur

```bash
  composer run dev
```

Cela lance automatiquement :
- 🌐 Le serveur Laravel : `php artisan serve --host=0.0.0.0 --port=8000`
- 📥 Le système de queue Laravel : `php artisan queue:listen --tries=1`
- 🔁 Le serveur WebSocket Laravel Reverb : `php artisan reverb:start --debug`
- 🎨 Le build et le hot reload des assets avec Vite : `npm run dev`

---

## 🧑‍💻 Contributeurs

- 👤 Erwann Varlet – [@github](https://github.com/erwnn20)
- 👤 Corentin Rey-Le Roux – [@github](https://github.com/t3nk5/)
