import '../app.js';

let waitingStartTime = Date.now();
let waitingTimer;
let messageTimeout;
let messageInterval;

const player1Element = document.getElementById("player-1");
const player1QueueId = player1Element.dataset.queueId;
const player1GameId = player1Element.dataset.gameId;

window.Echo.private(window.appConfig.ws.channels.queue.request + player1QueueId)
    .listen(window.appConfig.ws.alias.queue.request, (response) => {
        console.log(response);

        waitingStartTime = new Date(response.matchmakingQueue.entry_time).getTime();

        updatePlayer(1, {
            queue_id: response.matchmakingQueue.id,
            username: response.username,
            status: response.matchmakingQueue.status,
        })
    });

window.Echo.private(window.appConfig.ws.channels.queue.leave + player1QueueId)
    .listen(window.appConfig.ws.alias.queue.leave, (response) => {
        console.log(response);
        window.location.href = window.appConfig.routes.index;
    });

window.Echo.private(window.appConfig.ws.channels.game.start_check + player1GameId)
    .listen(window.appConfig.ws.alias.game.start_check, (response) => {
        console.log(response);
    });

document.addEventListener('DOMContentLoaded', function () {
    axios.post(window.appConfig.routes.queue.request)
        .then(response => console.log(response.data))
    // axios.post(window.appConfig.routes.queue.request)
    //     .then(response => console.log(response.data))

    startWaitingTimer();

    // simulateWaitingProcess();
    // loadOnlineStats();
});

function startWaitingTimer() {
    waitingTimer = setInterval(() => {
        const elapsed = Date.now() - waitingStartTime;
        const minutes = Math.floor(elapsed / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);

        document.getElementById('waiting-time').textContent =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
}

function updatePlayer(player_index, {queue_id, username, status}) {
    const playerElement = document.getElementById('player-' + player_index);
    if (queue_id != null) playerElement.dataset.queueId = queue_id;

    const avatarElement = playerElement.querySelector('.player-avatar');
    if (username != null) avatarElement.innerText = username[0];

    const nameElement = playerElement.querySelector('.player-name');
    if (username != null) nameElement.innerText = username;

    const statusElement = playerElement.querySelector('.player-status');
    if (status != null) {
        statusElement.classList.remove('waiting', 'ready', 'not-ready');
        switch (status) {
            case -1:
                statusElement.innerText = 'Pas Prêt'
                statusElement.classList.add('not-ready');
                break;
            case 0:
                statusElement.innerText = 'En Attente'
                statusElement.classList.add('waiting');
                break;
            case 1:
                statusElement.innerText = 'Prêt'
                statusElement.classList.add('ready');
                break;
            case 2:
                statusElement.innerText = 'En Partie'
                statusElement.classList.add('in-game');
                break;

        }
    }
}

document.getElementById('btn-accept').addEventListener('click', function () {
})
document.getElementById('btn-cancel').addEventListener('click', function () {
    axios.post(window.appConfig.routes.queue.leave, {
        'queue_id': document.getElementById('player-1').dataset.queueId,
    }).then(response => {
        console.log(response.data.message);
    });
})




//

// Mettre à jour quand un joueur est trouvé
function updatePlayerFound() {
    const player1 = document.getElementById('player-1');
    const player2 = document.getElementById('player-2');

    const p1Status = player1.querySelector('.player-status');
    p1Status.innerText = 'Pas Prêt'
    p1Status.classList.remove('waiting');
    p1Status.classList.add('not-ready');

    player2.querySelector('.player-avatar').innerText = '{{ $opponent->username[0] }}';
    player2.querySelector('.player-name').innerText = '{{ $opponent->username }}';

    const p2Status = player2.querySelector('.player-status');
    p2Status.innerText = 'Prêt'
    p2Status.classList.add('ready');

    document.getElementById('btn-accept').classList.remove('d-none');

    showMessage("Adversaire trouvé ! Acceptez la partie pour jouer...", "success");
}

// Simulation du processus d'attente
function simulateWaitingProcess() {
    const messages = [
        "Recherche d'un adversaire...",
        "Adversaire trouvé ! Préparation de la partie...",
        "En attente des joueurs..."
    ];

    let currentMessage = 0;

    messageInterval = setInterval(() => {
        if (currentMessage < messages.length) {
            document.getElementById('status-message').textContent = messages[currentMessage];

            if (currentMessage >= 1) {
                updatePlayerFound();
            }

            currentMessage++;
        } else {
            clearInterval(messageInterval);
            // Redirection vers la partie après 3 secondes
            // setTimeout(() => {
            //     window.location.href = '/game';
            // }, 3000);
        }
    }, 5000);
}

function loadOnlineStats() {
    setInterval(() => {
        // document.getElementById('active-games').textContent = {{ $active_games()  }};
    }, 5000);
}

// Accepter partie
function acceptGame() {
    const p1Status = document.querySelector('#player-1 .player-status');
    p1Status.innerText = 'Prêt'
    p1Status.classList.remove('not-ready');
    p1Status.classList.add('ready')

    showMessage("Joueurs prêts, la partie va commencer...", "success");

    // create game

    setTimeout(() => {
        // window.location.href = '{{ route('game.index', ['id' => 1]) }}';
    }, 1500);
}

// Afficher un message
function showMessage(text, type = "info") {
    const messageSection = document.getElementById('message-section');
    const messageText = document.getElementById('message-text');

    messageText.textContent = text;
    messageSection.className = `message-section show ${type}`;

    // Cacher le message après 5 secondes
    clearTimeout(messageTimeout);
    messageTimeout = setTimeout(() => {
        messageSection.classList.remove('show');
    }, 5000);
}

// Nettoyage avant fermeture de la page
window.addEventListener('beforeunload', function () {
    clearInterval(waitingTimer);
    clearTimeout(messageTimeout);
    clearTimeout(messageInterval);
});
