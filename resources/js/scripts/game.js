import '../app.js';

let gameActive = false;
let currentPlayer;

const gameId = document.getElementById('game').dataset.gameId;
const grid = document.getElementById('game-grid');
const player1Element = document.getElementById('player-1');
const player2Element = document.getElementById('player-2');
const gameLeaveBtn = document.getElementById('btn-leave-game')
const gameBoard = {
    grid: Array(6).fill().map(() => Array(7).fill(0)),

    init() {
        grid.innerHTML = '';

        for (const [row, r] of this.grid.entries()) {
            for (const [col, c] of r.entries()) {
                const cell = document.createElement('button');
                cell.className = 'cell';
                cell.onclick = () => {
                    dropPiece(col);
                };
                cell.dataset.row = row;
                cell.dataset.col = col;
                cell.disabled = true
                grid.appendChild(cell);
            }
        }
    },

    getRow(col) {
        for (let row = this.grid.length - 1; row >= 0; row--) {
            if (this.grid[row][col] === 0) return row
        }
        return -1
    },
    place({col, row = this.getRow(col), player_index}) {
        this.grid[row][col] = player_index;
        this.updateCell(row, col);
    },
    updateCell(row, col) {
        const player = this.grid[row][col];
        const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
        cell.className = `cell ${player === 1 ? 'red' : player === 2 ? 'yellow' : ''}`;
    },

    checkWin() {
        if (this.isBoardFull()) return 0 // draw

        const rows = this.grid.length;
        const cols = this.grid[0].length;
        const directions = [
            [0, 1],   // horizontal
            [1, 0],   // vertical
            [1, 1],   // diagonal \
            [1, -1]   // diagonal /
        ];

        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const player = this.grid[row][col];
                if (player === 0) continue;

                for (const [dr, dc] of directions) {
                    let count = 1;

                    for (let i = 1; i < 4; i++) {
                        const r = row + dr * i;
                        const c = col + dc * i;

                        if (
                            r >= 0 && r < rows &&
                            c >= 0 && c < cols &&
                            this.grid[r][c] === player
                        )
                            count++;
                        else break;
                    }

                    if (count === 4) {
                        highlightWinningCells(row, col, dr, dc);
                        return player;
                    }
                }
            }
        }

        return -1;
    },
    isBoardFull() {
        return this.grid[0].every(cell => cell !== 0);
    },
    endGame(message) {
        showMessage(message, 'success');
        setActiveButtons(false);
    },
}

window.Echo.private(window.appConfig.ws.channels.game.update + gameId)
    .listen(window.appConfig.ws.alias.game.update, (response) => {
        gameActive = response.game.status === -1;
        currentPlayer = response.next.player.player_index

        for (const player of response.game.players)
            document.querySelector(`#player-${player.player_index} span`).innerText = player.user.name;

        gameBoard.grid = Array(6).fill().map(() => Array(7).fill(0));
        for (const move of response.game.moves)
            gameBoard.place({col: move.column, player_index: move.player_index});

        if (gameActive) {
            if (gameBoard.checkWin() !== -1) {
                axios.post(window.appConfig.routes.game.update, {
                    'game_id': gameId,
                    'status': gameBoard.checkWin(),
                }).then(response => console.log(response.data));
            } else {
                setActiveButtons(true);
                updateGameStatus({
                    name: response.game.players[currentPlayer - 1].user.name,
                    index: currentPlayer,
                })
            }
        } else {
            gameBoard.endGame(`${response.game.players[response.game.status - 1].user.name} à gagné !`);

        }
    });

document.addEventListener('DOMContentLoaded', function () {
    axios.post(window.appConfig.routes.game.update, {'game_id': gameId})
        .then(response => console.log(response.data));

    gameBoard.init();
});

function dropPiece(col) {
    if (!gameActive) return;
    if (gameBoard.getRow(col) === -1) return;

    axios.post(window.appConfig.routes.game.place, {
        'game_id': gameId,
        'column': col,
    }).then(response => console.log(response))
}

function setActiveButtons(active) {
    const columnBtns = document.querySelectorAll('.cell');
    columnBtns.forEach(btn => btn.disabled = !active);

    if (active)
        gameLeaveBtn.classList.add('d-none');
    else
        gameLeaveBtn.classList.remove('d-none');
}

function highlightWinningCells(row, col, dx, dy) {
    const winningCells = [{row, col}];
    for (let i = 1; i < 4; i++) {
        const newRow = row + dx * i;
        const newCol = col + dy * i;
        if (newRow >= 0 && newRow < 6 && newCol >= 0 && newCol < 7
            /*&& gb[newRow][newCol] === currentPlayer*/) {
            winningCells.push({row: newRow, col: newCol});
        } else {
            break;
        }
    }

    for (let i = 1; i < 4; i++) {
        const newRow = row - dx * i;
        const newCol = col - dy * i;
        if (newRow >= 0 && newRow < 6 && newCol >= 0 && newCol < 7
            /*&& gb[newRow][newCol] === currentPlayer*/) {
            winningCells.push({row: newRow, col: newCol});
        } else {
            break;
        }
    }

    winningCells.forEach(({row, col}) => {
        const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
        cell.classList.add('winning');
    });
}

function showMessage(text, type) {
    const messageElement = document.getElementById('game-message');
    messageElement.textContent = text;
    messageElement.className = `message ${type}`;
    messageElement.style.display = 'block';
}

function updateGameStatus({name, index}) {
    const status = document.getElementById('game-status');
    const player1Indicator = player1Element.querySelector('.player-indicator');
    const player2Indicator = player2Element.querySelector('.player-indicator');

    status.textContent = `Au tour de ${name}`;

    player1Indicator.classList.remove('active');
    player2Indicator.classList.remove('active');
    if (index === 1)
        player1Indicator.classList.add('active');
    else if (index === 2)
        player2Indicator.classList.add('active');
}

gameLeaveBtn.addEventListener('click', () => {
    window.location.href = window.appConfig.routes.index;
})
