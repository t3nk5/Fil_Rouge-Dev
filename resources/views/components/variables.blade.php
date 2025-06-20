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
                start_check: '{{ route('game.start-check') }}',
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
                    start_check: 'game-channel.start-check-',
                }
            },
            alias: {
                queue: {
                    join: '.queue-join',
                    request: '.queue-request',
                    leave: '.queue-leave',
                },
                game: {
                    start_check: '.game-start-check',
                }
            },
        },
    };
</script>
