<script type="module">
    window.appConfig = {
        routes: {
            index: '{{ route('index') }}',
            queue: {
                index: '{{ route('queue.index') }}',
                join: '{{ route('queue.join') }}',
                request: '{{ route('queue.request') }}',
                leave: '{{ route('queue.leave') }}',
            },
            game: {
                index: '{{ route('game.index.template') }}/',
                pre_update: '{{ route('game.pre-update') }}',
                update: '{{ route('game.update') }}',
                place: '{{ route('game.place') }}',
            },
        },
        ws: {
            channels: {
                queue: {
                    join: 'queue-channel.join-',
                    request: 'queue-channel.request-',
                    leave: 'queue-channel.leave-',
                },
                game: {
                    pre_update: 'game-channel.pre-update-',
                    start: 'game-channel.start-',
                    update: 'game-channel.update-',
                }
            },
            alias: {
                queue: {
                    join: '.queue-join',
                    request: '.queue-request',
                    leave: '.queue-leave',
                },
                game: {
                    pre_update: '.game-pre-update',
                    start: '.game-start',
                    update: '.game-update',
                }
            },
        },
    };
</script>
