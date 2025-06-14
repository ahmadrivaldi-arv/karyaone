<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', 'Default Title')
        - {{ config('app.name', 'KaryaOne') }}
    </title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    {{ $slot }}

    @livewire('notifications')

    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
