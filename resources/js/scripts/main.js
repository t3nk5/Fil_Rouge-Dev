import '../app.js';

const userId = document.getElementById('username')?.dataset.userId;

function playGame() {
    const playBtn = document.getElementById('play-btn');
    const playIcon = playBtn.querySelector('.play-icon');

    playBtn.classList.add('loading');
    playIcon.textContent = '⟳';

    axios.post(window.appConfig.routes.queue.join)
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.log(error);

            const playBtn = document.getElementById('play-btn');
            const playIcon = playBtn.querySelector('.play-icon');

            playBtn.classList.remove('loading');
            playIcon.textContent = '▶';

            if (error.response?.status === 401) window.location.href = window.appConfig.routes.login;
        });
}

window.Echo.private(window.appConfig.ws.channels.queue.join + userId)
    .listen(window.appConfig.ws.alias.queue.join, () => {
        const playBtn = document.getElementById('play-btn');
        const playIcon = playBtn.querySelector('.play-icon');

        window.location.href = window.appConfig.routes.queue.index;

        playBtn.classList.remove('loading');
        playIcon.textContent = '▶';
    });

document.getElementById('play-btn').addEventListener('click', playGame);

document.addEventListener('keydown', (e) => {
    if (e.code === 'Space' || e.code === 'Enter') {
        e.preventDefault();
        playGame();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        animateCounter({elementId: 'games-played'});
        animateCounter({elementId: 'games-won'});
        animateCounter({elementId: 'win-rate', suffix: '%'})
    }, 500);
});

function animateCounter({elementId, targetValue, suffix = ''}) {
    const element = document.getElementById(elementId);
    targetValue = targetValue ?? element.dataset.value;
    let currentValue = 0;
    const increment = targetValue / 30;

    const timer = setInterval(() => {
        currentValue += increment;
        if (currentValue >= targetValue) {
            currentValue = targetValue;
            clearInterval(timer);
        }
        element.textContent = Math.round(currentValue) + suffix;
    }, 50);
}
