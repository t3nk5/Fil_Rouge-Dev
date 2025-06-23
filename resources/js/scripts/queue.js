import '../app.js';

let hasOpponent = false;
let waitingStartTime = Date.now();
let waitingTimer;
let messageTimeout;

const selfPlayerElement = document.getElementById("player-1");
const selfQueueId = selfPlayerElement.dataset.queueId;
const gameId = selfPlayerElement.dataset.gameId;

const readyBtn = document.getElementById("btn-ready");
const notReadyBtn = document.getElementById("btn-not-ready");
const cancelBtn = document.getElementById("btn-cancel");

window.Echo.private(window.appConfig.ws.channels.queue.leave + selfQueueId)
    .listen(window.appConfig.ws.alias.queue.leave, (response) => {
        console.log(response);
        window.location.href = window.appConfig.routes.index;
    });

window.Echo.private(window.appConfig.ws.channels.game.pre_update + gameId)
    .listen(window.appConfig.ws.alias.game.pre_update, (response) => {
        // player 1 (self)
        const selfQueue = response.queues[selfQueueId];
        waitingStartTime = new Date(selfQueue.entry_time).getTime();
        updatePlayer(1, {
            status: selfQueue.status,
        })

        readyBtn.classList.add("d-none");
        notReadyBtn.classList.add("d-none");
        if (selfQueue.status === -1) { // currently not ready
            readyBtn.classList.remove("d-none");
        } else if (selfQueue.status === 1) { // currently ready
            notReadyBtn.classList.remove("d-none");
        } else if (selfQueue.status === 2) {
            cancelBtn.classList.add("d-none");
            window.location.href = window.appConfig.routes.game.index + response.game_id;
        }

        // player 2 (opponent)
        const opponentQueueId = Object.keys(response.queues)
            .find(id => id !== selfQueueId);
        if (opponentQueueId) {
            const opponentQueue = response.queues[opponentQueueId];
            updatePlayer(2, {
                username: opponentQueue.user.name,
                status: opponentQueue.status,
            })

            if (!hasOpponent)
                showMessage("Adversaire trouvé ! Acceptez la partie pour jouer...", "success")
            hasOpponent = true;
        } else {
            updatePlayer(2, {
                avatar: '?',
                username: 'En attente...',
                status: 'default',
            })
            hasOpponent = false;
        }
    });

window.Echo.private(window.appConfig.ws.channels.game.start + gameId)
    .listen(window.appConfig.ws.alias.game.start, (response) => {
        window.location.href = window.appConfig.routes.game.index + response.game.id;
    });

document.addEventListener('DOMContentLoaded', function () {
    axios.post(window.appConfig.routes.game.pre_update, {'game_id': gameId})
        .then(response => console.log(response.data));

    startWaitingTimer();
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

function updatePlayer(player_index, {queue_id, username, status, avatar}) {
    const playerElement = document.getElementById('player-' + player_index);
    if (queue_id != null) playerElement.dataset.queueId = queue_id;
    playerElement.classList.remove('d-none');

    const avatarElement = playerElement.querySelector('.player-avatar');
    if (avatar != null) avatarElement.innerText = avatar[0];
    else if (username != null) avatarElement.innerText = username[0];

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
                statusElement.innerText = 'En Jeu'
                statusElement.classList.add('in-game');
                break;
            default:
                statusElement.innerText = 'Recherche'
                break;
        }
    }
}

readyBtn.addEventListener('click', function () {
    axios.post(window.appConfig.routes.game.pre_update, {
        'game_id': gameId,
        'update': {
            'queue_id': selfQueueId,
            'status': 'ready',
        }
    }).then(response => console.log(response.data));
})
notReadyBtn.addEventListener('click', function () {
    axios.post(window.appConfig.routes.game.pre_update, {
        'game_id': gameId,
        'update': {
            'queue_id': selfQueueId,
            'status': 'not-ready',
        }
    }).then(response => console.log(response.data));
})
cancelBtn.addEventListener('click', function () {
    axios.post(window.appConfig.routes.queue.leave, {
        'queue_id': selfQueueId,
    }).then(response => console.log(response.data));
})

function showMessage(text, type = "info") {
    const messageSection = document.getElementById('message-section');
    const messageText = document.getElementById('message-text');

    messageText.textContent = text;
    messageSection.className = `message-section show ${type}`;

    clearTimeout(messageTimeout);
    messageTimeout = setTimeout(() => {
        messageSection.classList.remove('show');
    }, 5000);
}

window.Echo.private(window.appConfig.ws.channels.stats.queue)
    .listen(window.appConfig.ws.alias.stats.queue, (response) => {
        document.getElementById('active-games').innerText = response.data.current_games;
    });

window.addEventListener('beforeunload', function () {
    clearInterval(waitingTimer);
    clearTimeout(messageTimeout);
});
