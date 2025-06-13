<x-layout title="Jeu - Puissance 4" :css="asset('css/game/style.css')">
    <x-header :user="$user"/>

    <main class="main-content">
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

        <div class="message" id="game-message"></div>

        <div class="controls">
            <button id="btn-leave-game" class="btn btn-primary d-none" onclick="leaveGame()">Quitter la partie</button>
        </div>

    </main>

    <x-footer/>

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
                    const cell = document.createElement('button');
                    cell.className = 'cell';
                    cell.onclick = () => { dropPiece(col); };
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
                    // generer game move

                    // Mettre à jour l'affichage
                    updateCell(row, col, currentPlayer);

                    if (checkWin(row, col)) {
                        endGame(`Joueur ${currentPlayer} gagne !`);
                        return;
                    }

                    if (isBoardFull()) {
                        endGame('Match nul !');
                        return;
                    }

                    switchPlayer();
                    return;
                }
            }
        }

        function updateCell(row, col, player) {
            const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
            cell.className = `cell ${player === 1 ? 'red' : 'yellow'}`;
        }

        function switchPlayer() {
            currentPlayer = currentPlayer === 1 ? 2 : 1;
            updateGameStatus();
        }

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

        function highlightWinningCells(row, col, dx, dy) {
            const winningCells = [{row, col}];
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

        function isBoardFull() {
            return gameBoard[0].every(cell => cell !== 0);
        }

        function endGame(message) {
            gameActive = false;
            showMessage(message, 'success');

            // Désactiver les boutons de colonne
            const columnBtns = document.querySelectorAll('.column-btn');
            columnBtns.forEach(btn => btn.disabled = true);

            document.getElementById('btn-leave-game').classList.remove('d-none');
        }

        function leaveGame() {
            setTimeout(() => {
             window.location.href = '{{ route('index') }}';
            })
        }

        // Afficher un message
        function showMessage(text, type) {
            const messageEl = document.getElementById('game-message');
            messageEl.textContent = text;
            messageEl.className = `message ${type}`;
            messageEl.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function () {
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
</x-layout>
