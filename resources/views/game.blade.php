<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puissance 4</title>
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
</head>
<body>
<div class="container">
    <h1>PUISSANCE 4</h1>

    <div class="game-info">
        <div class="player-info">
            <div class="player-indicator red active" id="player1-indicator"></div>
            <span>Joueur 1</span>
        </div>
        <div class="game-status" id="game-status">
            Au tour du Joueur 1
        </div>
        <div class="player-info">
            <div class="player-indicator yellow" id="player2-indicator"></div>
            <span>Joueur 2</span>
        </div>
    </div>

    <div class="game-board">
        <div class="column-header">
            <button class="column-btn" onclick="dropPiece(0)">1</button>
            <button class="column-btn" onclick="dropPiece(1)">2</button>
            <button class="column-btn" onclick="dropPiece(2)">3</button>
            <button class="column-btn" onclick="dropPiece(3)">4</button>
            <button class="column-btn" onclick="dropPiece(4)">5</button>
            <button class="column-btn" onclick="dropPiece(5)">6</button>
            <button class="column-btn" onclick="dropPiece(6)">7</button>
        </div>

        <div class="grid" id="game-grid">
            <!-- Les cellules seront générées par JavaScript -->
        </div>
    </div>

    <div class="controls">
        <button class="btn btn-primary" onclick="resetGame()">Nouvelle Partie</button>
        <button class="btn btn-secondary" onclick="undoMove()">Annuler</button>
    </div>

    <div class="message" id="game-message"></div>
</div>

<script>
    // Variables du jeu
    let currentPlayer = 1;
    let gameBoard = Array(6).fill().map(() => Array(7).fill(0));
    let gameActive = true;
    let moveHistory = [];

    // Initialisation de la grille
    function initializeGrid() {
        const grid = document.getElementById('game-grid');
        grid.innerHTML = '';

        for (let row = 0; row < 6; row++) {
            for (let col = 0; col < 7; col++) {
                const cell = document.createElement('div');
                cell.className = 'cell';
                cell.dataset.row = row;
                cell.dataset.col = col;
                grid.appendChild(cell);
            }
        }
    }

    // Fonction pour jouer un coup
    function dropPiece(col) {
        if (!gameActive) return;

        // Trouver la première ligne libre dans la colonne
        for (let row = 5; row >= 0; row--) {
            if (gameBoard[row][col] === 0) {
                gameBoard[row][col] = currentPlayer;
                moveHistory.push({row, col, player: currentPlayer});

                // Mettre à jour l'affichage
                updateCell(row, col, currentPlayer);

                // Vérifier la victoire
                if (checkWin(row, col)) {
                    endGame(`Joueur ${currentPlayer} gagne !`);
                    return;
                }

                // Vérifier l'égalité
                if (isBoardFull()) {
                    endGame('Match nul !');
                    return;
                }

                // Changer de joueur
                switchPlayer();
                return;
            }
        }
    }

    // Mettre à jour une cellule
    function updateCell(row, col, player) {
        const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
        cell.className = `cell ${player === 1 ? 'red' : 'yellow'}`;
    }

    // Changer de joueur
    function switchPlayer() {
        currentPlayer = currentPlayer === 1 ? 2 : 1;
        updateGameStatus();
    }

    // Mettre à jour le statut du jeu
    function updateGameStatus() {
        const status = document.getElementById('game-status');
        const player1Indicator = document.getElementById('player1-indicator');
        const player2Indicator = document.getElementById('player2-indicator');

        status.textContent = `Au tour du Joueur ${currentPlayer}`;

        if (currentPlayer === 1) {
            player1Indicator.classList.add('active');
            player2Indicator.classList.remove('active');
        } else {
            player2Indicator.classList.add('active');
            player1Indicator.classList.remove('active');
        }
    }

    // Vérifier la victoire
    function checkWin(row, col) {
        const directions = [
            [0, 1],   // horizontal
            [1, 0],   // vertical
            [1, 1],   // diagonal \
            [1, -1]   // diagonal /
        ];

        for (let [dx, dy] of directions) {
            let count = 1;

            // Vérifier dans une direction
            for (let i = 1; i < 4; i++) {
                const newRow = row + dx * i;
                const newCol = col + dy * i;
                if (newRow >= 0 && newRow < 6 && newCol >= 0 && newCol < 7 &&
                    gameBoard[newRow][newCol] === currentPlayer) {
                    count++;
                } else {
                    break;
                }
            }

            // Vérifier dans l'autre direction
            for (let i = 1; i < 4; i++) {
                const newRow = row - dx * i;
                const newCol = col - dy * i;
                if (newRow >= 0 && newRow < 6 && newCol >= 0 && newCol < 7 &&
                    gameBoard[newRow][newCol] === currentPlayer) {
                    count++;
                } else {
                    break;
                }
            }

            if (count >= 4) {
                highlightWinningCells(row, col, dx, dy);
                return true;
            }
        }

        return false;
    }

    // Surligner les cellules gagnantes
    function highlightWinningCells(row, col, dx, dy) {
        const winningCells = [{row, col}];

        // Trouver toutes les cellules gagnantes
        for (let i = 1; i < 4; i++) {
            const newRow = row + dx * i;
            const newCol = col + dy * i;
            if (newRow >= 0 && newRow < 6 && newCol >= 0 && newCol < 7 &&
                gameBoard[newRow][newCol] === currentPlayer) {
                winningCells.push({row: newRow, col: newCol});
            } else {
                break;
            }
        }

        for (let i = 1; i < 4; i++) {
            const newRow = row - dx * i;
            const newCol = col - dy * i;
            if (newRow >= 0 && newRow < 6 && newCol >= 0 && newCol < 7 &&
                gameBoard[newRow][newCol] === currentPlayer) {
                winningCells.push({row: newRow, col: newCol});
            } else {
                break;
            }
        }

        // Appliquer l'animation
        winningCells.forEach(({row, col}) => {
            const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
            cell.classList.add('winning');
        });
    }

    // Vérifier si le plateau est plein
    function isBoardFull() {
        return gameBoard[0].every(cell => cell !== 0);
    }

    // Terminer le jeu
    function endGame(message) {
        gameActive = false;
        showMessage(message, 'success');

        // Désactiver les boutons de colonne
        const columnBtns = document.querySelectorAll('.column-btn');
        columnBtns.forEach(btn => btn.disabled = true);
    }

    // Afficher un message
    function showMessage(text, type) {
        const messageEl = document.getElementById('game-message');
        messageEl.textContent = text;
        messageEl.className = `message ${type}`;
        messageEl.style.display = 'block';
    }

    // Réinitialiser le jeu
    function resetGame() {
        currentPlayer = 1;
        gameBoard = Array(6).fill().map(() => Array(7).fill(0));
        gameActive = true;
        moveHistory = [];

        initializeGrid();
        updateGameStatus();

        // Réactiver les boutons
        const columnBtns = document.querySelectorAll('.column-btn');
        columnBtns.forEach(btn => btn.disabled = false);

        // Cacher le message
        document.getElementById('game-message').style.display = 'none';
    }

    // Annuler le dernier coup
    function undoMove() {
        if (moveHistory.length === 0 || !gameActive) return;

        const lastMove = moveHistory.pop();
        gameBoard[lastMove.row][lastMove.col] = 0;

        const cell = document.querySelector(`[data-row="${lastMove.row}"][data-col="${lastMove.col}"]`);
        cell.className = 'cell';

        currentPlayer = lastMove.player;
        updateGameStatus();
    }

    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        initializeGrid();
        updateGameStatus();
    });

    // Ici vous pouvez ajouter l'intégration Pusher pour le multijoueur
    // Exemple de structure pour Pusher :
    /*
    const pusher = new Pusher('YOUR_APP_KEY', {
        cluster: 'YOUR_CLUSTER'
    });

    const channel = pusher.subscribe('game-channel');

    channel.bind('move-made', function(data) {
        // Traiter le coup reçu
        dropPiece(data.column);
    });

    function sendMove(column) {
        // Envoyer le coup via Laravel/Pusher
        fetch('/make-move', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ column: column })
        });
    }
    */
</script>
</body>
</html>
