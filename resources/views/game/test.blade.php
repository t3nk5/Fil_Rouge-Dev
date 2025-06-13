<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puissance 4</title>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            forceTLS: true
        });

        const channel = pusher.subscribe('game-channel');
        channel.bind('App\\Events\\GameUpdatedEvent', function(data) {
            location.reload();
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
        }
        td {
            width: 50px;
            height: 50px;
            border: 2px solid #333;
            text-align: center;
            vertical-align: middle;
            font-size: 24px;
        }
        .R {
            background-color: red;
        }
        .J {
            background-color: yellow;
        }
        a {
            text-decoration: none;
            font-size: 24px;
        }
    </style>
</head>
<body>

<h1>🎮 Puissance 4</h1>

@if (session('winner'))
    <h2 style="color: green;">
        🎉 Le joueur {{ session('winner') }} a gagné !
    </h2>
@else
    <h2>Tour du joueur : {{ session('turn') }}</h2>
@endif

<table>
    <tr>
        @for ($col = 0; $col < count($grid[0]); $col++)
            <td>
                @if (!session('winner'))
                    <a href="/play/{{ $col }}">⬇️</a>
                @endif
            </td>
        @endfor
    </tr>

    @foreach ($grid as $row)
        <tr>
            @foreach ($row as $cell)
                <td class="{{ $cell }}">
                    @if ($cell)
                        {{ $cell }}
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
</table>

<form action="{{ route('test.restart')  }}" method="GET">
    <button type="submit">🔄 Rejouer</button>
</form>

</body>
</html>
