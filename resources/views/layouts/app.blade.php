<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>
        @yield('title', 'Default Title')
        - {{ config('app.name', 'KaryaOne') }}
    </title>
</head>

<body>
    {{ $slot }}

    @yield('page-scripts')
</body>

</html>
