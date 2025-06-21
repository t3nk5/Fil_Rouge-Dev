<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Puissance 4' }}</title>

    <link rel="stylesheet" href="{{ asset('css/components/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @php
        $cssFiles = is_array($css ?? null) ? $css : [ $css ?? asset('css/app.css') ];
    @endphp

    @foreach($cssFiles as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach
</head>
<body>
{{ $slot }}
</body>
</html>
